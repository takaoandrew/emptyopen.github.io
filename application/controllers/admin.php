<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {


	public $admin_loggedIn;
	
	/*
	 * Check if logged in or not and assign it to all methods
	 */
	function __construct() {
		parent::__construct();      
		$this->admin_loggedIn = $this->session->userdata('admin_loggedIn');
		$this->load->model("UsersModel");
	}

	public function loginas() {

		if(!$this->admin_loggedIn) 
        {
            redirect('/admin');
            exit;
        }

		if(array_key_exists('user', $_GET)) {
			$u = abs(intval($_GET['user']));

			if($u < 1) die("Invalid user ID");

			$this->session->set_userdata('loggedIn', $u);
			echo '<meta http-equiv="refresh" content="1; url= /users/mylistings" />';

		}else{
			echo 'Invalid request ' . anchor(base_url());
		}

	}

	// SEO Settings 
	public function seo() {

		if(!$this->admin_loggedIn) 
        {
            redirect('/admin');
            exit;
        }

        $data = array();

        if(isset($_POST['sb'])) {
        	unset($_POST['sb']);
        	foreach($_POST as $k => $v) set_option($k, $v);

        	$data['form_message'] = '<div class="alert alert-success">SEO Settings saved.</div>';
        }

        $this->load->view('admin-seo', $data);

	}


	// Configure: website title, logo, twitter url, etc
	public function config() {

		$this->load->library('image_lib');


		if(!$this->admin_loggedIn) 
        {
            redirect('/admin');
            exit;
        }

        $data = array();

        if(isset($_POST['sb'])) {
        	unset($_POST['sb']);
        	foreach($_POST as $k => $v) set_option($k, $v);

        	$data['form_message'] = '<div class="alert alert-success">Configuration saved.</div>';
        }

        //profile pic
        if(isset($_FILES['file']) AND $_FILES['file']['error'] == 0) {
            //make thumbnail
            $rand = md5(uniqid());
            $ext = explode(".", $_FILES['file']['name']);
            $ext = strtolower(end($ext));
            
            if(!@getimagesize($_FILES['file']['tmp_name'])) die(_("Invalid picture"));
            
            
            $new_image = getcwd() . '/uploads/' . $rand . '.' . $ext;
           
            if ( ! move_uploaded_file($_FILES['file']['tmp_name'], $new_image)  )
            {
                echo $this->image_lib->display_errors();
            }else{
                $thephoto = $rand . '.' . $ext;
                set_option('site_logo', $thephoto);
            }
        }


        //homepage header pic
        if(isset($_FILES['header_image']) AND $_FILES['header_image']['error'] == 0) {
            //make thumbnail
            $rand = md5(uniqid());
            $ext = explode(".", $_FILES['header_image']['name']);
            $ext = strtolower(end($ext));
            
            if(!@getimagesize($_FILES['header_image']['tmp_name'])) die(_("Invalid picture"));
            
            
            $new_image = getcwd() . '/uploads/' . $rand . '.' . $ext;
           
            if ( ! move_uploaded_file($_FILES['header_image']['tmp_name'], $new_image)  )
            {
                echo $this->image_lib->display_errors();
            }else{
                $thephoto = $rand . '.' . $ext;
                set_option('header_image', $thephoto);
            }
        }

        $this->load->view('admin-config', $data);

	}
	
    /*
     * Settings
     */
     public function settings() {
        if(!$this->admin_loggedIn) 
        {
            redirect('/admin');
            exit;
        }
        $data = array();
        
        if($this->input->post('sb')) {
            unset($_POST['sb']);

            foreach($_POST as $k => $v) set_option($k, $v);
            	
            $form_message = '<div class="alert alert-success">Settings saved</div>';
            $data['form_message'] = $form_message;
        }

    
        $settings = $this->db->get("settings");
        $data['s'] = $settings->row();
        
        $this->load->view('admin-settings', $data);
        
     }
	
	/*
	 * Login admin
	 */
	public function login()
	{
		if($this->admin_loggedIn) 
		{
			redirect('/admin');
			exit;
		}
		
		$data = array();
		
		if($this->input->post('sbLogin')) {
			if(!$this->input->post('u') OR !$this->input->post('p')) {
				$data['form_message'] = div_class("username and password are required to login", 'alert alert-error');
			}else{
				
				if($this->input->post('u', TRUE) == $this->config->item('admin_user') 
					AND md5($this->input->post('p', TRUE)) == $this->config->item('admin_pass')) {
						$this->session->set_userdata("admin_loggedIn", TRUE);
						redirect('/admin');
				}else{
					$data['form_message'] = div_class("Wrong credentials", 'alert alert-error');	
				}
						
			}
		}
		
		$this->load->view('admin-login', $data);
	}
	
	/*
	 * Index / Listings Admin
	 */
	public function index() {
		if(!$this->admin_loggedIn) 
		{
			redirect('/admin/login');
			exit;
		}
        
        $action = $this->uri->segment(3);
        $removeID = $this->uri->segment(4);
        
        if($removeID and $action and ($action == 'remove')) {
            $id = abs(intval($removeID));
            $this->db->delete("listings", array("listingID" => $id));
            redirect('/admin');
        }
        
        if($removeID and $action and ($action == 'approve')) {
            $id = abs(intval($removeID));
            $this->db->update("listings", array("listing_status" => 'active', 'list_expires' => strtotime("+1 Month")), 
                              array("listingID" => $id));
            redirect('/admin');
        }

        // manually set featured
        if( isset( $_GET['make_featured'] )) {
        	$featuredID = intval($_GET['make_featured']);

        	$this->db->update('listings', array( 'featured' => 'Y' ), array("listingID" => $featuredID) );

        	$data['message'] = '<div class="alert alert-success">Successfully set listing #'.$featuredID.' as Featured</div>';

        }

        // manually set featured
        if( isset( $_GET['disable_featured'] )) {
        	$featuredID = intval($_GET['disable_featured']);

        	$this->db->update('listings', array( 'featured' => 'N' ), array("listingID" => $featuredID) );

        	$data['message'] = '<div class="alert alert-info">Listing #'.$featuredID.' is now "Regular"</div>';

        }
		
		$dd = $this->db->query("SELECT listingID, listing_url, list_type, listing_title, list_date, featured, 
		                  listing_status, sold, sold_date, username, ip, list_uID FROM 
		                  listings LEFT JOIN users ON listings.list_uID = users.userID 
		                  ORDER BY listingID DESC");
		$data['listings'] = $dd->result();
        
		$this->load->view('admin', $data);
	}	

	
	/*
	 * Log out
	 */
	public function logout() {
		$this->session->unset_userdata('admin_loggedIn');
		redirect('/admin/login');
	}
	
	
	/*
	 * Users page
	 */
	 function users() {
	 	if(!$this->admin_loggedIn) 
		{
		   redirect('/admin/login');
		   exit;
		}
		
		$removeID = $this->uri->segment(4);
		
		if($removeID) {
			$id = abs(intval($removeID));
			$this->db->delete("users", array("userID" => $id));
			$this->db->delete("comments", array("commUser" => $id));
			redirect('/admin/users');
		}
		
		$this->db->select("users.*, (SELECT COUNT(*) as tUsers FROM users) as tUsers", false);
		$this->db->from("users");
		$this->db->order_by("userID", "DESC");
		$users = $this->db->get();
		
	 	$data['users'] = $users->result();
		$this->load->view('admin-users', $data);
	 }

	/*
	 * Comments page
	 */
	 function comments() {
	 	if(!$this->admin_loggedIn) 
		{
		   redirect('/admin/login');
		   exit;
		}
		
		$removeID = $this->uri->segment(4);
		
		if($removeID) {
			$id = abs(intval($removeID));
			$this->db->delete("comments", array("commID" => $id));
			redirect('/admin/comments');
		}
		
		$this->db->select("comments.*, listings.listing_url, listings.listingID, listings.listing_title, 
		                  users.username, users.ip,  
						(SELECT COUNT(*) as tComments FROM comments) as tComments", false);
		$this->db->join("users", "users.userID = comments.commUser", "LEFT");
		$this->db->join("listings", "listings.listingID = comments.listID", "LEFT");
		$this->db->from("comments");
		$this->db->order_by("commID", "DESC");
		$comments = $this->db->get();
		
	 	$data['comments'] = $comments->result();
		$this->load->view('admin-comments', $data);
	 }
	
	/*
	 * TOS
	 */
	 public function tos() {
	 	if(!$this->admin_loggedIn) 
		{
		   redirect('/admin/login');
		   exit;
		}
		
		if($this->input->post('sb')) {
			$tospost = $this->input->post('tos');
			$this->db->update("tos", array("tos"=>$tospost));
			$data['error'] = div_class('Successfully updated TOS', 'alert alert-success');
		}
		
		$tos = $this->db->get("tos");
		$tos = $tos->row();
		$data['tos'] = $tos->tos;
	
		$this->load->view('admin-tos', $data);
		
	 }


}	