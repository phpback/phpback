<?php
/*********************************************************************
PHPBack
Ivan Diaz <ivan@phpback.org>
Copyright (c) 2014 PHPBack
http://www.phpback.org
Released under the GNU General Public License WITHOUT ANY WARRANTY.
See LICENSE.TXT for details.
**********************************************************************/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct(){
		parent::__construct();
        session_start();

		$this->load->helper('url');
		$this->load->model('get');

		$this->lang->load('default', $this->get->get_setting('language'));

        if(@isset($_SESSION['phpback_userid']) && $this->get->isbanned($_SESSION['phpback_userid']) != 0){
            date_default_timezone_set('America/Los_Angeles');
            $ban = $this->get->isbanned($_SESSION['phpback_userid']);
            if($ban <= date("Ymd") && $ban != -1){
                $this->get->unban($_SESSION['phpback_userid']);
                return;
            }
            session_destroy();
            if(@isset($_COOKIE['phpback_sessionid'])){
                $this->get->verify_token($_COOKIE['phpback_sessionid']);
                setcookie('phpback_sessionid', '', time()-3600, '/');
            }
            if($ban != -1){
                for($i = 0; $i < 366; $i++){
                    if(date('Ymd', strtotime("+$i days")) == $ban) break;
                }
            }
            else $i = -1;

            header('Location: '. base_url() .'home/login/banned/' . $i);
            return;
        }
	}
	public function index(){

        // debug message to show where you are, just for the demo
        // load views. within the views we can echo out $songs and $amount_of_songs easily
        $data['categories'] = $this->get->get_categories();
        $data['title'] = $this->get->get_setting('title');
        
        $data['ideas']['completed'] = $this->get->get_ideas_custom('id', 1, 0, 10, array('completed'));
        $data['ideas']['started'] = $this->get->get_ideas_custom('id', 1, 0, 10, array('started'));
        $data['ideas']['planned'] = $this->get->get_ideas_custom('id', 1, 0, 10, array('planned'));
        $data['ideas']['considered'] = $this->get->get_ideas_custom('id', 1, 0, 10, array('considered')); 

        if(@!isset($_SESSION['phpback_userid']) && @isset($_COOKIE['phpback_sessionid'])){
            $result = $this->get->verify_token($_COOKIE['phpback_sessionid']);
            if($result != 0){
                $user = $this->get->get_user_info($result);
                $_SESSION['phpback_userid'] = $user->id;
                $_SESSION['phpback_username'] = $user->name;
                $_SESSION['phpback_useremail'] = $user->email;
                setcookie('phpback_sessionid',  $this->get->new_token($_SESSION['phpback_userid']), time()+3600*24*30, '/');
                header('Location: '. base_url() .'home');
                return;
            }
        }

        $data['lang'] = $this->lang->language;

		$this->load->view('_templates/header', $data);
		$this->load->view('home/index', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
		
	}
	
	public function category($id, $name = "", $order = "votes", $type = "desc", $page = '1')
    {
        if(!$this->get->category_exists($id)){
            header('Location: ' . base_url() . 'home');
            return;
        }
        $data['ideas'] = $this->get->get_ideas_by_category($id, $order, $type, $page);
        $data['categories'] = $this->get->get_categories();
        $data['title'] = $this->get->get_setting('title');
        $total = $this->get->get_ideas_aprroved($id);
        $data['max_results'] = (int) $this->get->get_setting('max_results');
        $data['page'] = (int) $page;
        $data['pages'] = $total / $data['max_results'];
        $data['pages'] = (int) $data['pages'];
        $data['id'] = $id;
        if(($total % $data['max_results']) > 0) $data['pages'] ++;
        $data['type'] = $type;
        $data['order'] = $order;

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/category_ideas', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function search(){
        $query = $this->input->post('query');
        $data['ideas'] = $this->get->search_ideas($query);
        $data['categories'] = $this->get->get_categories();
        $data['title'] = $this->get->get_setting('title');

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/search_results', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function idea($id){
        $idea = $this->get->get_idea_by_id($id);
        if($idea === false){
            header('Location: ' . base_url() . 'home');
            return;
        }
        $ideauser = $this->get->get_user_info($idea->authorid);
        $idea->user = $ideauser->name;
        $comments = $this->get->get_comments_by_id($id);
        foreach($comments as $comment){
            $u = $this->get->get_user_info($comment->userid);
            $comment->user = $u->name;
        }
        $data['title'] = $this->get->get_setting('title');
        $data['comments'] = $comments;
        $data['categories'] = $this->get->get_categories();
        $data['idea'] = $idea;

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/view_idea', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    
    public function profile($id, $error=0){
        $data['user'] = $this->get->get_user_info($id);
        if($data['user'] === false){
            header('Location: ' . base_url() . 'home');
            return;
        }
        $data['logs'] = $this->get->get_logs('user', $id);
        $data['comments'] = $this->get->get_user_comments($id, 20);
        $data['categories'] = $this->get->get_categories();
        $data['ideas'] = $this->get->get_user_ideas($id);
        $data['title'] = $this->get->get_setting('title');
        $data['error'] = $this->input->post('error', true);
        if(@isset($_SESSION['phpback_userid']) && $data['user']->id == $_SESSION['phpback_userid']){
            $data['votes'] = $this->get->get_user_votes($_SESSION['phpback_userid']);
        }

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/user', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    
    public function login($error = "NULL", $ban=0){
        if(@!isset($_SESSION['phpback_userid']) && @isset($_COOKIE['phpback_sessionid'])){
            $result = $this->get->verify_token($_COOKIE['phpback_sessionid']);
            if($result != 0){
                $user = $this->get->get_user_info($result);
                $_SESSION['phpback_userid'] = $user->id;
                $_SESSION['phpback_username'] = $user->name;
                $_SESSION['phpback_useremail'] = $user->email;
                setcookie('phpback_sessionid',  $this->get->new_token($_SESSION['phpback_userid']), time()+3600*24*30, '/');
                header('Location: '. base_url() .'home');
                return;
            }
        }

        if(@isset($_SESSION['phpback_userid'])){
            header('Location: '. base_url() .'home');
            return;
        }

        $data['categories'] = $this->get->get_categories();
        $data['title'] = $this->get->get_setting('title');
        $data['error'] = $error;
        $data['ban'] = $ban;

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/login', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function postidea($error = "none"){
        
        $data['categories'] = $this->get->get_categories();
        $data['title'] = $this->get->get_setting('title');
        $data['error'] = $error;
        $data['POST'] = $_POST;

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/post_idea', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function register($error = "NULL"){

        $data['recaptchapublic'] = $this->get->get_setting('recaptchapublic');
        $data['categories'] = $this->get->get_categories();
        $data['title'] = $this->get->get_setting('title');
        $data['error'] = $error;

        $data['lang'] = $this->lang->language;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/register', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }
}
?>