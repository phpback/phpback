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
	public function __construct() {
		parent::__construct();
        session_start();

		$this->load->helper('url');
		$this->load->model('get');
		$this->load->model('post');

		$this->lang->load('default', $this->get->getSetting('language'));

        $this->verifyBanning();
	}
	public function index() {
        $this->autoLoginByCookie();

        //Use this function to parse $freename variables getDisplayHelpers();
        $data = $this->getDefaultData();
        $data['welcomeTitle'] = $this->get->getSetting('welcometext-title');
        $data['welcomeDescription'] = $this->get->getSetting('welcometext-description');

        $data['ideas'] = array(
            'completed' => $this->get->getIdeas('id', 1, 0, 10, array('completed')),
            'started' => $this->get->getIdeas('id', 1, 0, 10, array('started')),
            'planned' => $this->get->getIdeas('id', 1, 0, 10, array('planned')),
            'considered' => $this->get->getIdeas('id', 1, 0, 10, array('considered')),
        );

		$this->load->view('_templates/header', $data);
		$this->load->view('home/index', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);

	}

	public function category($id, $name = "", $order = "votes", $type = "desc", $page = '1') {
        if (!$this->get->categoryExists($id)){
            header('Location: ' . base_url() . 'home');
            return;
        }

        $data = $this->getDefaultData();
        $data['ideas'] = $this->get->getIdeasByCategory($id, $order, $type, $page);
        $data['category'] = $data['categories'][$id];
        $total = $this->get->getQuantityOfApprovedIdeas($id);
        $data['max_results'] = (int) $this->get->getSetting('max_results');
        $data['page'] = (int) $page;
        $data['pages'] = (int) ($total / $data['max_results']);
        if(($total % $data['max_results']) > 0) $data['pages']++;
        $data['type'] = $type;
        $data['order'] = $order;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/category_ideas', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function search() {
        $data = $this->getDefaultData();

        $query = $this->input->post('query');
        $data['ideas'] = $this->get->getIdeasBySearchQuery($query);

        $this->load->view('_templates/header', $data);
		$this->load->view('home/search_results', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function idea($id) {
        $idea = $this->get->getIdea($id);

        if ($idea === false) {
            header('Location: ' . base_url() . 'home');
            return;
        }

        $ideaUserName = $this->get->getUser($idea->authorid)->name;
        $idea->user = $ideaUserName;
        $comments = $this->get->getCommentsByIdea($id);

        foreach($comments as $comment){
            $userName = $this->get->getUser($comment->userid)->name;
            $comment->user = $userName;
        }

        $data = $this->getDefaultData();
        $data['comments'] = $comments;
        $data['idea'] = $idea;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/view_idea', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }


    public function profile($id, $error=0) {
        $data = $this->getDefaultData();

        $data['user'] = $this->get->getUser($id);

        if ($data['user'] === false) {
            header('Location: ' . base_url() . 'home');
            return;
        }

        $data['logs'] = $this->get->get_logs('user', $id);
        $data['comments'] = $this->get->getUserComments($id, 20);
        $data['ideas'] = $this->get->getUserIdeas($id);


        $data['error'] = $this->input->post('error', true);
        if(@isset($_SESSION['phpback_userid']) && $data['user']->id == $_SESSION['phpback_userid']){
            $data['votes'] = $this->get->getUserVotes($_SESSION['phpback_userid']);
        }

        $this->load->view('_templates/header', $data);
		$this->load->view('home/user', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }


    public function login($error = "NULL", $ban=0) {
        if(@!isset($_SESSION['phpback_userid']) && @isset($_COOKIE['phpback_sessionid'])){
            $result = $this->get->verifyToken($_COOKIE['phpback_sessionid']);
            if($result != 0){
                $user = $this->get->getUser($result);
                $this->get->setSessionUserValues($user);
                $this->get->setSessionCookie();
                header('Location: '. base_url() .'home');
                return;
            }
        }

        if(@isset($_SESSION['phpback_userid'])) {
            header('Location: '. base_url() .'home');
            return;
        }

        $data = $this->getDefaultData();
        $data['error'] = $error;
        $data['ban'] = $ban;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/login', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    public function postidea($error = "none") {
        $data = $this->getDefaultData();
        $data['error'] = $error;
        $data['POST'] = array(
					'title' => $this->input->post('title'),
					'catid' => $this->input->post('catid'),
					'desc' => $this->input->post('desc')
				);

        $this->load->view('_templates/header', $data);
        $this->load->view('home/post_idea', $data);
        $this->load->view('_templates/menu', $data);
        $this->load->view('_templates/footer', $data);
    }

    public function register($error = "NULL") {
        $data = $this->getDefaultData();
        $data['recaptchapublic'] = $this->get->getSetting('recaptchapublic');
        $data['error'] = $error;

        $this->load->view('_templates/header', $data);
		$this->load->view('home/register', $data);
		$this->load->view('_templates/menu', $data);
		$this->load->view('_templates/footer', $data);
    }

    private function getDefaultData() {
        return array(
            'title' => $this->get->getSetting('title'),
            'categories' => $this->get->getCategories(),
            'lang' => $this->lang->language,
        );
    }

    private function verifyBanning() {
        if (@isset($_SESSION['phpback_userid']) && ($ban = $this->get->getBanValue($_SESSION['phpback_userid'])) != 0) {
            date_default_timezone_set('America/Los_Angeles');

            //Remove ban if ban expired
            if ($ban <= date("Ymd") && $ban != -1) {
                $this->post->unban($_SESSION['phpback_userid']);
                return;
            }

            session_destroy();
            $this->destroyUserCookie();

            if ($ban != -1) {
                for($i = 0; $i < 366; $i++){
                    if(date('Ymd', strtotime("+$i days")) == $ban) break;
                }
            }
            else $i = -1;

            header('Location: '. base_url() .'home/login/banned/' . $i);
            exit;
        }
    }

    private function destroyUserCookie() {
        if(@isset($_COOKIE['phpback_sessionid'])){
            $this->get->verifyToken($_COOKIE['phpback_sessionid']);
            setcookie('phpback_sessionid', '', time()-3600, '/');
        }
    }

    private function autoLoginByCookie() {
        if(@!isset($_SESSION['phpback_userid']) && @isset($_COOKIE['phpback_sessionid'])) {
            $result = $this->get->verifyToken($_COOKIE['phpback_sessionid']);
            if($result != 0) {
                $user = $this->get->getUser($result);
                $_SESSION['phpback_userid'] = $user->id;
                $_SESSION['phpback_username'] = $user->name;
                $_SESSION['phpback_useremail'] = $user->email;
                setcookie('phpback_sessionid',  $this->get->new_token($_SESSION['phpback_userid']), time()+3600*24*30, '/');
                header('Location: '. base_url() .'home');
                exit;
            }
        }
    }
}
