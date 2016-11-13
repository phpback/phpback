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

require_once(__DIR__ . "../../../vendor/autoload.php");
use \VisualAppeal\AutoUpdate;

class Admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('get');

        $this->version = '1.3.1';
	}

	public function index($error = 'no'){
		session_start();
        $data = array();
        if($error == "error"){
            $data['error'] = 'yes';
            $this->load->view('admin/header', $data);
            $this->load->view('admin/login', $data);
            return;
        }
        if(!isset($_SESSION['phpback_isadmin'])){
            $this->load->view('admin/header', $data);
            $this->load->view('admin/login', $data);
        }
        elseif($_SESSION['phpback_isadmin'] == 0){
            $data['error'] = 'noadmin';
            $this->load->view('admin/header', $data);
            $this->load->view('admin/login', $data);
        }
        else{
            header('Location: ' . base_url() . 'admin/dashboard/');
            exit;
        }
	}

    public function dashboard(){
        $this->start();
        $data = array();
        //$data['ideas'] = $this->get->get_new_ideas(10);
        //$data['flags'] = $this->get->get_flags();
        $data['logs'] = $this->get->get_last_logs();

        $this->load->view('admin/dashboard/header');
        $this->load->view('admin/dashboard/index', $data);
    }

    public function ideas(){
        $this->start();
        $data = array();
        $data['newideas'] = $this->get->get_new_ideas(150);
        $data['newideas_num'] = $this->get->get_new_ideas_num();
        $data['flags'] = $this->get->get_flags();
        $data['categories'] = $this->get->getCategories();
        if(!@isset($_POST['search'])){
            $data['form'] = array(
                "status-completed" => 0,
                "status-started" => 0,
                "status-planned" => 1,
                "status-considered" => 1,
                "status-declined" => 0,
                "orderby" => "votes",
                "isdesc" => 1
                );
            $cat = array();
            foreach ($data['categories'] as $t) {
                $cat[] = $t->id;
                $s = "category-".$t->id;
                $data['form'][$s] = 1;
            }
            $st = array("considered", "planned");
            $data['toall'] = 0;
        }
        else{
            $data['form'] = array(
                "status-completed" => ($this->input->post('status-completed', true)) ? 1 : 0,
                "status-started" => ($this->input->post('status-started', true)) ? 1 : 0,
                "status-planned" => ($this->input->post('status-planned', true)) ? 1 : 0,
                "status-considered" => ($this->input->post('status-considered', true)) ? 1 : 0,
                "status-declined" => ($this->input->post('status-declined', true)) ? 1 : 0,
                "orderby" => $this->input->post('orderby', true),
                "isdesc" => ($this->input->post('isdesc', true)) ? 1 : 0
                );
            $st = array();
            if($this->input->post('status-completed', true)) $st[] = "completed";
            if($this->input->post('status-started', true)) $st[] = "started";
            if($this->input->post('status-planned', true)) $st[] = "planned";
            if($this->input->post('status-considered', true)) $st[] = "considered";
            if($this->input->post('status-declined', true)) $st[] = "declined";

            $cat = array();
            foreach ($data['categories'] as $t) {
                $s = "category-".$t->id;
                if($this->input->post('category-' . $t->id, true)){
                    $cat[] = $t->id;
                    $data['form'][$s] = 1;
                }
                else{
                    $data['form'][$s] = 0;
                }
            }
            $data['toall'] = 1;
        }

        $this->redirectIfNotAlphaNumeric(array(
            $data['form']['orderby']
        ));

        $data['ideas'] = $this->get->getIdeas($data['form']['orderby'], $data['form']['isdesc'], 0, 150, $st, $cat);

        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/ideas', $data);
    }
    public function users($idban=0){
        $this->start(2);
        $data = array();

        $data['users'] = $this->get->get_users();
        $data['banned'] = $this->get->get_users('banned', 100);

        if($idban) $data['idban'] = $idban;
        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/users', $data);
    }

    public function system(){
        $this->start(3);
        $data = array();
        $data['settings'] = $this->get->get_all_settings();
        $data['adminusers'] = $this->get->get_admin_users();
        $data['categories'] = $this->get->getCategories();
        $data['version'] = $this->version;

        if ($this->get->getAutoUpdaterEnabled()) {
            $update = new AutoUpdate(__DIR__ . '/temp', __DIR__ . '/../../', 60);
            $update->setCurrentVersion($this->version); // Current version of your application. This value should be from a database or another file which will be updated with the installation of a new version
            $update->setUpdateUrl('http://www.phpback.org/upgrade/'); //Replace the url with your server update url
            $update->checkUpdate();

            $data['lastVersion'] = $update->getLatestVersion();
            $data['isLastVersion'] = !$update->newVersionAvailable();
        } else {
            $data['lastVersion'] = $this->version;
            $data['isLastVersion'] = false;
        }

        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/system', $data);
    }

    private function start($level = 1){
        session_start();
        if(!isset($_SESSION['phpback_isadmin']) || $_SESSION['phpback_isadmin'] < $level){
            header('Location: ' . base_url() . 'admin/');
            exit;
        }
    }

    private function redirectIfNotAlphaNumeric($textList) {
        foreach ($textList as $text) {
            if (!$this->isAlphaNumeric($text)) {
                header('Location: ' . base_url() . 'admin/');
                exit;
            }
        }
    }

    private function isAlphaNumeric($text) {
        return ctype_alnum($text);
    }
}
