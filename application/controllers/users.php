<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Controller {
		
	public $loggedIn;
	
	
	/*
	 * Check if logged in or not and assign it to all methods
	 */
	function __construct() {
		parent::__construct();      
		$this->loggedIn = $this->session->userdata('loggedIn');
        $this->load->model("UsersModel");
	}
    
    /*
     * Messages/Read body
     */
     public function read_message() {
         if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        //estabilish fromID
        $userID = abs(intval($this->loggedIn));
        
        //estabilish msgID
        $msgID = $this->uri->segment(3);
        $msgID = abs(intval($msgID));
        
        if(!$msgID) die("No msgID");
        
        //get msg body
        $this->db->select("body")->from('messages')->where("msgID", $msgID)->where("toID", $userID);
        $rs = $this->db->get(); 
        
        if(count($rs)) {
            echo nl2br($rs->row()->body);
        }else{
            echo _('There is no message with this ID or you dont have the rights to read it');
        }
        
     }
    
    /*
     * Messages/Send
     */
     public function message() {
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        //estabilish fromID
        $userID = abs(intval($this->loggedIn));
        
        //estabilish toID
        $toID = $this->uri->segment(3);
        $toID = abs(intval($toID));
        
        
        //check if in reply to
        if($this->uri->segment(4) AND ($this->uri->segment(4) == 'replyto') AND $this->uri->segment(5)) {
            $replyTo = abs(intval($this->uri->segment(5)));
            if(!$replyTo) die("Invalid replyto");
            
            $this->db->select("subject");
            $this->db->from("messages");
            $this->db->where("msgID", $replyTo);
            $rs = $this->db->get()->row();
            
            if($rs) {
                $data['reply_subject'] = _('Re : ') . $rs->subject;
            }
            
        }
        
        if(!$toID) die(_('You received this page in error. Go Back!'));
        
        if($userID == $toID) die(_('You cannot send a message to yourself!'));
        
        
        if($this->input->post('sb_msg')) {
            
            $subject = trim(strip_tags($this->input->post('subject')));
            $body = trim(strip_tags($this->input->post('body')));;
            
            if(strlen($subject) < 5 || strlen($body) < 10) {
                $data['form_message'] = "<div class='alert alert-danger'>";
                $data['form_message'] .= _('Subject min 5 characters and body min 10 please.');
                $data['form_message'] .= '</div>';
            }else{
            
                $insert = array();
                $insert['fromID'] = $userID;
                $insert['toID'] = $toID;
                $insert['subject'] = $subject;
                $insert['body'] = $body;
                $insert['msg_date'] = time();
                
                $this->db->insert("messages", $insert);

                // email the listing owner 
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $query = $this->db->query("SELECT username, email FROM users WHERE userID = ? LIMIT 1", array($toID));
                $user_data = $query->row();

                $to = $user_data->email;

                $body = 'Hi there <srong>'.$user_data->username.'</strong>,<br/><br/>';

                $body .= 'You have received a new message:<br/>';
                $body .= '<br/>Please login to view the message!';
                $body .= '<br/><a href="'.base_url().'?login=yes">'.base_url().'?login=yes</a><br /><br />';
                $body .= 'Then go to your messages inbox<br/><br/>';
                $body .= '<a href="'.base_url().'users/inbox">'.base_url().'users/inbox</a><br /><br />';

                mail($to, "New Message Received", $body, $headers);
                
                $data['form_message'] = "<div class='alert alert-success'>";
                $data['form_message'] .= _('Your message has been sent to the recipient.');
                $data['form_message'] .= '</div>';
                
            }
        }
        
        if(!isset($data)) $data = array();
        $this->load->view('user-msg', $data);
        
     }
    
    /*
     * Messages/Inbox
     */
     public function inbox() {
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        
        //estabilish userID
        $userID = abs(intval($this->loggedIn));
        
        //get messages for this user
        $this->db->select("messages.*, username");
        $this->db->from("messages");
        $this->db->where(array("toID" => $userID));
        $this->db->join("users", "messages.fromID=users.userID");
        $this->db->order_by("msgID", "DESC");
        $messages = $this->db->get();
        
        $data['messages'] = $messages->result();
        
        if(!$messages->num_rows()) {
            $data['msg'] = _('You have no messages');
        }
        
        $this->load->view('user-inbox', $data);
        
     }
    
    /*
     * Bids made
     */
     public function offers() {
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        //estabilish userID
        $userID = abs(intval($this->loggedIn));
        
        //if sold
        if($this->uri->segment(3) AND ($this->uri->segment(3) == 'sold') AND $this->uri->segment(4)) {
            $listingID = abs(intval($this->uri->segment(4)));
            
            $lastBid = $this->db->select("MAX(amount) as amt")->from("bids")->where("bid_listing", $listingID);
            $lastBid = $this->db->get()->row()->amt;
            
            if($this->db->update('listings', 
                            array("sold" => 'Y', 'sold_date' => time(), "sold_price" => $lastBid), 
                            array("listingID" => $listingID, "list_uID" => $userID)))
                            {
                                echo '<meta http-equiv="refresh" content="0;url=/users/offers">';
                                exit;
                            }
        }
        
        //if rejected
        if($this->uri->segment(3) AND ($this->uri->segment(3) == 'reject') AND $this->uri->segment(4)) {
            $listingID = abs(intval($this->uri->segment(4)));
            if($this->db->delete('bids', array("bidID" => $listingID, "owner_ID" => $userID)))
                            {
                                echo '<meta http-equiv="refresh" content="0;url=/users/offers">';
                                exit;
                            }
        }
        
        //get bids
        $bids = $this->db->query("SELECT bidID,listingID, listing_title, listing_url, bid_date, username, amount, sold, sold_date FROM bids 
                        JOIN listings ON listingID = bid_listing
                        JOIN users ON bidder_ID = userID 
                        WHERE listingID IN (SELECT CONCAT_WS(',', listingID) FROM listings WHERE list_uID = $userID)
                        ORDER BY bidID DESC");
        if($bids->num_rows()) {
            $bids = $bids->result();
            $data['bids'] = $bids;
        }else{
            $data['msg'] = _('No offers yet');
        }
        
        $this->load->view('user-offers.php', $data);                        
        
     }


    /*
     * Bids made
     */
     public function bids() {
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        //estabilish userID
        $userID = abs(intval($this->loggedIn));
        
        
        //get bids
        $bids = $this->db->query("SELECT bidID,listingID, listing_title, listing_url, bid_date, username, amount, sold, sold_date FROM bids 
                        JOIN listings ON listingID = bid_listing
                        JOIN users ON list_uID = userID 
                        WHERE bidder_ID = $userID
                        ORDER BY bidID DESC");
        if($bids->num_rows()) {
            $bids = $bids->result();
            $data['bids'] = $bids;
        }else{
            $data['msg'] = _('No bids made');
        }
        
        $this->load->view('user-bids.php', $data);                        
        
     }
    
    
    /*
     * User Listings
     */
     public function mylistings() {
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        $this->load->library('table');
        
        $userID = $this->loggedIn;
        
        $this->db->select("listingID, 
        
                           CONCAT('<a href=\"/listings/', listingID ,'/mylistings\">', listing_url, '</a>') AS listing_url, 
                           
                           CONCAT('$', FORMAT(bin,2 )) AS BIN, 
                           
                           FROM_UNIXTIME(list_date, '%D %b %Y') as list_date,
        
                           CASE list_expires WHEN 0 THEN '-' ELSE FROM_UNIXTIME(list_expires, '%D %b %Y') END 
                           AS list_expires, 
                           
                           sold, 
                            
                           CASE sold_date WHEN 0 THEN '-' ELSE FROM_UNIXTIME(sold_date, '%D %b %Y') END 
                           AS sold_date, 
                           
                           CASE WHEN list_expires < '".time()."' THEN 
                           CONCAT('<a href=\"/payments/relist/', listingID, '\" class=\"btn btn-xs btn-warning\">%s</a>') ELSE '-' END 
                           AS payLink, 
                           
                           CONCAT('<a href=\"/users/goedit/', listingID, '\" class=\"btn btn-xs btn-default\">%s</a>') as editl, featured", false);
                           
        $userListings = $this->db->get_where("listings", array("list_uID" => $userID));
        
        $tmpl = array ( 'table_open'  => '<table class="table table-bordered table-hover">' );

        $this->table->set_template($tmpl);
        $this->table->set_heading('#ID', 'URL', 'Price', 'Date', 'Expires', 'Sold', 'Sold Date', 'Relist', '<b class="icon-edit"></b>');
        $data['table'] = $this->table->generate($userListings);
        
        $data['listings'] = $userListings->result();
        
        $data['listings_count'] = $userListings->num_rows();
        
        $this->load->view('mylistings', $data);
        
     }
     
     /*
      * Redirect to edit
      */
      public function goedit() {
        ob_start();
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        
        $id = $this->uri->segment(3);
        $id = abs(intval($id));
        
        if(!$id) die("Edit #ID wrong");
        
        //check if owner is correct
        $listing = $this->db->get_where("listings", array("listingID" => $id, "list_uID" => $this->loggedIn));
        
        if(!$listing->num_rows()) {
            die(_("This listing isn't yours. Don't try edit other people listings"));
        }else{
            $this->session->set_userdata("listingID", $id);
            redirect('/users/newlisting');
        }
        ob_end_flush();
      }
	
	
	/*
	 * User home
	 */
	public function index()
	{
		if(!$this->loggedIn) 
		{
			redirect('/users/login');
			exit;
		}
		
		if($this->input->post('sb_signup')) {
			if(!$this->input->post('email') OR !$this->input->post('password')) {
				$data['form_message'] = div_class("Email and password are required", 'alert alert-danger');
			}else{
			    
				$this->db->where(array("email" => $this->input->post('email', TRUE)));
				$this->db->where("userID != " . is_user_logged_in());
				$user = $this->db->get("users");
				
				if(count($user->result())) {
					$data['form_message'] = '<div class="alert alert-warning">';
					$data['form_message'] .= _('Username/Email taken, please chose another one.');
					$data['form_message'] .= '</div>';
				}else{
				    
                //profile pic
                if(isset($_FILES['file']) AND $_FILES['file']['error'] == 0) {
                    //make thumbnail
                    $rand = md5(uniqid());
                    $ext = explode(".", $_FILES['file']['name']);
                    $ext = strtolower(end($ext));
                    
                    if(!@getimagesize($_FILES['file']['tmp_name'])) die(_("Invalid picture"));
                    
                    $config['image_library'] = 'gd2';
                    #$config['source_image'] = getcwd() .'/uploads/' .  $rand . '.' . $ext;
                    $config['source_image'] = $_FILES['file']['tmp_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = TRUE;
                    $config['width']     = 48;
                    $config['height']   = 48;
                    $config['new_image'] = getcwd() . '/uploads/' . $rand . '.' . $ext;
                    
                    $this->load->library('image_lib', $config); 
                    
                    $this->image_lib->resize();
                    
                    if ( ! $this->image_lib->resize())
                    {
                        echo $this->image_lib->display_errors();
                    }else{
                        $thephoto = $rand . '.' . $ext;
                        $this->db->where("userID", is_user_logged_in());
                        $this->db->update("users", array('photo' => $thephoto));
                    }
                }
				
				$this->db->where("userID", is_user_logged_in());
				$this->db->update("users", array('email' => $this->input->post('email'), 
												'password' => md5($this->input->post('password')), 
												'about' => trim(strip_tags($this->input->post('about')))));
				$data['form_message'] = div_class("Account updated", 'alert alert-success');
				
				}
			}
		}
		
		$user = $this->db->get_where("users", array("userID" => is_user_logged_in()));
	 	$user = $user->row(); 
		$data['user'] = $user; 
		
		$this->load->view('user-account', $data);
	}
	
	
	/*
	 * User Login
	 */
	 public function login() {
        ob_start();
        
	 	if($this->loggedIn) 
		{
			redirect('/users');
			exit;
		}

        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) OR $_SERVER['HTTP_X_REQUESTED_WITH']!="XMLHttpRequest") {
            redirect("/?login=yes");
        } 
		
		$data = array();
		
		if($this->input->post('sbLogin')) {
			$user = $this->input->post('uname', TRUE);
			$pass = $this->input->post('upwd', TRUE);
			
			if(!empty($user) AND !empty($pass)) {
				$this->db->where(array("username" => $user));
				$this->db->where(array("password" => md5($pass)));
				$user = $this->db->get("users");
				
				if(count($user->result())) {
					echo '<div class="alert alert-success">Ok, redirecting..</div>';
					foreach($user->result() as $u) {
						$this->session->set_userdata('loggedIn', $u->userID);
					}
                    //echo '<meta http-equiv="refresh" content="1; url= /users" />';
                    echo '<script>window.location.href = "/users"</script>';
				}else{
					echo '<div class="alert alert-danger">'._('Invalid username and/or password').'</div>';
				}
				
			}else{
				echo '<div class="alert alert-danger">'._('Invalid username and password').'</div>';
			}
			
		}
		
	 }
	
	
	/*
	 * Logout function
	 */
	public function logout() {
		$this->session->unset_userdata('loggedIn');
		redirect('/users/login');
	}
	
	
	/*
	 * Register Form/Page
	 */
	public function join() {
		if($this->loggedIn) 
		{
			redirect('/users');
			exit;
		}
		
		$this->load->view('join-now');
	}
	
	
	/*
	 * Register via AJAX
	 */
	public function ajax_join() {
		
		if($this->input->post('sb_signup')) {
		
			unset($_POST['sb_signup']);
				
			$insert = array();
			
			foreach($this->input->post() as $k=>$v) {
				if($this->input->post($k, TRUE) != "") {
					$insert[$k] = $this->input->post($k, TRUE);
				}else{
					print '<div class="alert alert-danger">';
					print _('All fields are mandatory');
					print '</div>';
					exit;
				}
			}
			
			$this->db->where(array("username" => $this->input->post('username', TRUE)));
			$this->db->or_where(array("email" => $this->input->post('email', TRUE)));
			$user = $this->db->get("users");
			
			if(count($user->result())) {
				print '<div class="alert alert-danger">';
				print _('Username/Email taken, please chose another one.');
				print '</div>';
				exit;
			}
			
			$insert['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
			$insert['password'] = md5($insert['password']);
			
			if($this->db->insert("users", $insert)) {
				$this->session->set_userdata('loggedIn', $this->db->insert_id());
				print '<div class="alert alert-success">';
				print _('You are now logged in. <a href="/users">My Account</a>');
				print '</div>';
			}else{
				print '<div class="alert alert-danger">';
				print _('DB Error');
				print '</div>';
			}
			
		
		}else{
			print '<div class="alert alert-danger">';
			print _('-No post-');
			print '</div>';
		}
		
		
	}


	/*
	 * User Profiles
	 */
	 public function profile() {
	 	 $username = trim(strip_tags($this->uri->segment(3)));
		 
		 if(!$username) {
		 	 $data['error'] = _('User not found');
		 	 $this->load->view('user-profiles', $data); 
		 }else{
		 	$user = $this->db->get_where("users", array("username" => $username));
		 	$user = $user->row(); 
			$data['user'] = $user;

			
			if(count($user)) {
			    //get listings
				$this->db->select("listingID, listing_title, listing_url, bin, CONCAT('$', FORMAT(`bin`,0)) as `starting_`, 
                         site_age, `starting_` as starting_bid, 
                         CONCAT('$', FORMAT(rev_avg,0)) as rev_avg,  
                         list_date,list_expires, 
                         FORMAT(traffic_avg_visits,0) as traffic_avg_visits, pagerank, 
                         PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'), FROM_UNIXTIME(site_age, '%Y%m')) AS diff", false);
				$this->db->from("listings");
				$this->db->where("list_uID = $user->userID");
				$playlist= $this->db->get();
				$data['listings'] = $playlist->result();
                $data['tl'] = $playlist->num_rows();
                
                //get total bids
                $this->db->select("COUNT(*) as bids")->from("bids")->where("bidder_ID", $user->userID);
                $b = $this->db->get()->row();
                $data['tbids'] = $b->bids;
			}else{
				$data['listings'] = new stdClass;
			}
			 
			$this->load->view('user-profiles', $data);
			
		 }
		 
	 }


    /*
     * Add new listing
     */
     public function newlisting() {
        ob_start();
        if(!$this->loggedIn) 
        {
            redirect('/users/login');
            exit;
        }
        $this->load->model('ValidateURL');
        
        $validateURL = new ValidateURL();
        
        $data = array();
        $percentage = 10;
        
        if(isset($_POST['sbStep1'])) {
            //check URL
            $url = $this->input->post('listing_url');
            
            $data['basic_icon'] = 'glyphicon glyphicon-remove';    
            $data['desc_icon'] = 'glyphicon glyphicon-remove';
            $data['siteage_icon'] = 'glyphicon glyphicon-remove';
            $data['revenue_icon'] = 'glyphicon glyphicon-remove';
            $data['pricing_icon'] = 'glyphicon glyphicon-remove';
            $data['traffic_icon'] = 'glyphicon glyphicon-remove';
            $data['monetization_icon'] = 'glyphicon glyphicon-remove';
            $data['unique_icon'] = 'glyphicon glyphicon-remove';
            $data['payments_icon'] = 'glyphicon glyphicon-remove';
            $data['tags_icon'] = 'glyphicon glyphicon-remove';
            $data['verify_icon'] = 'glyphicon glyphicon-remove';
            
            if($validateURL->isValidURL($url)) {
                if($validateURL->websiteListed($url)) {
                    $data['err_msg'] = _('Website/Domain already listed on our site.');
                }else{
                    
                    $dbURL = $validateURL->dbURLify($url);
                    
                    $this->db->insert("listings", 
                                      array(
                                            "list_uID" => $this->loggedIn, 
                                            "listing_url" => $dbURL, 
                                            "alexa" => get_alexa($dbURL), 
                                            "pagerank" => get_pagerank($dbURL), 
                                            "list_expires" => strtotime("+30 Days"),
                                            "list_date" => time()));
                    
                    $insertID = $this->db->insert_id(); 
                    
                    if($insertID) {
                        $this->session->set_userdata('listingID', $insertID);
                        redirect('/users/goedit/' . $insertID);
                        exit;
                        $data['step'] = TRUE;
                    }else{
                        $data['err_msg'] = _('Could not add domain to database.');
                    }
                }
            }else{
                $data['err_msg'] = _('URL could not be reached');
            }
                
        }

        if($this->session->userdata('listingID')) {
            
            $id = $this->session->userdata('listingID');
            $id = abs(intval($id));
            
            $listing = $this->db->get_where("listings", array("listingID" => $id, "list_uID" => $this->loggedIn));
            
            if(!$listing->num_rows()) {
                echo _("Listing doesn't seem to be yours");
                $this->session->unset_userdata('listingID');
                echo '<meta http-equiv="refresh" content="2; url=/home"/>"';
                exit;
            }
            
            $l = $listing->row();
            
            $data['l'] = $l;
            
            
            //update percentage and basic icon
            if((!empty($l->listing_title) AND $l->starting_ > 0 AND $l->bin > 0)) $percentage += 20;
            $data['basic_icon'] = (!empty($l->listing_title) AND $l->starting_ > 0 AND $l->bin > 0) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            //update percentage and description icon 
            if(!empty($l->listing_description)) $percentage += 15;   
            $data['desc_icon'] = !empty($l->listing_description) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            //update site age icon
            if($l->site_age != 0) $percentage += 5;
            $data['siteage_icon'] = ($l->site_age != 0) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            
            //revenue icon & percentage update
            if($l->revenue_details != "" && $l->rev_avg != "") $percentage += 10; 
            $data['revenue_icon'] = ($l->revenue_details != "" && $l->rev_avg != "") ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            
            $data['pricing_icon'] = 'glyphicon glyphicon-remove';
            
            
            //traffic icon & percentage
            if($l->traffic_details != "" && $l->traffic_avg_visits != "" && $l->traffic_avg_views != "") $percentage += 10;
            $data['traffic_icon'] = ($l->traffic_details != "" && $l->traffic_avg_visits != "" && $l->traffic_avg_views != "") ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            //update monetization icon and percentage
            if(!empty($l->monetization)) $percentage += 10;
            $data['monetization_icon'] = !empty($l->monetization) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            //update unique icon and percentage
            //$percentage += 5;
            $data['unique_icon'] = 'glyphicon glyphicon-ok';
            
            
            //payments accepted icon & percentage
            if(!empty($l->payment_options)) $percentage += 5;
            $data['payments_icon'] = !empty($l->payment_options) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            
            //tags icon
            if(!empty($l->tag_niche) && !empty($l->tag_implementation) &&! empty($l->tag_type)) $percentage += 5;
            $data['tags_icon'] = (!empty($l->tag_niche) && !empty($l->tag_implementation) && !empty($l->tag_type)) ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
           
            //verify icon
            if($l->verified == 'Y') $percentage += 10;
            $data['verify_icon'] = ($l->verified == 'Y') ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove';
            
            
            //get listing attachments
            $att = $this->db->get_where("attachments", array("listID" => $l->listingID));
            $data['att'] = $att->result();
            
        }
        
        $data['percent'] = $percentage;
        $data['id'] = @$id;
        $data['listing'] = @$l;
        $this->load->view('newlisting', $data);
        
        ob_end_flush();
     }

      /*
       * Unset listingID from session to allow new listing startover
       */
       public function clearlisting() {
           ob_start();
           
           if(!$this->loggedIn) 
           {
                redirect('/users/login');
                exit;
           }
           
           $id = $this->session->userdata("listingID");
           $id = abs(intval($id));
           
           #$this->db->delete("listings", array("listingID" => $id, "list_uID" => $this->loggedIn));
           
           $this->session->unset_userdata('listingID');
           
           header("Location: /users/newlisting");
           ob_end_flush();
           
       }

     /*
      * Edit Listing
      */
      public function editlisting() {
          ob_start();
          if(!$this->loggedIn) 
          {
              redirect('/users/login');
              exit;
          }
          
          $this->htmlheader();
          
          $id = $this->uri->segment(4);
          $action = $this->uri->segment(5);
          
          if(!$id || !$action) die(div_class('Error! No ID / Action!'));
          
          
          $listing = $this->db->get_where("listings", array("listingID" => $id));
          #$listing = $listing->result();
          
          if ($listing->num_rows() > 0)
          {
              $listing = $listing->row();
          }else{
              $listing = null;
          }
          
          switch($action) {
              case "basic":
                  ?>
                  <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms">
                      <label><?=_('Listing Type')?>:</label>
                      <input type="radio" name="list_type" value="domain" <?php if($listing && $listing->list_type == 'domain') echo 'checked=""'; ?>/> Domain Only
                      <input type="radio" name="list_type" value="website" <?php if($listing && $listing->list_type == 'website') echo 'checked=""'; ?>/> Website
                      
                      <label><?=_('Listing Title')?>:</label>
                      <input type="text" name="listing_title" value="<?php if($listing) echo $listing->listing_title; ?>" class="input-xxlarge required"/><br/>
                      
                      <label><?=_('Starting Price')?>:</label>
                      <input type="number" name="starting_" value="<?php if($listing) echo $listing->starting_; ?>" class="input-xxlarge required"/><br/>
                      
                      <label><?=_('Reserve Price')?>:</label>
                      <input type="number" name="reserve" value="<?php if($listing) echo $listing->reserve; ?>" class="input-xxlarge required"/><br/>
                      
                      <label><?=_('BIN Price')?>:</label>
                      <input type="number" name="bin" value="<?php if($listing) echo $listing->bin; ?>" class="input-xxlarge required"/><br/>
                      
                      <input type="submit" name="sb" value="<?=_('Update')?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div>
                  <?php
              break;
              
              case "description":
                  ?>
                  <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms">
                  <textarea name="listing_description" id="listing_description" rows="12" class="input-xxlarge required" style="width:650px;"><?php echo $listing->listing_description; ?></textarea>
                  <br/>    
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div>    
                  <?php
              break;
              
              case "site_age":
                  $months = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');

                  ?>
                  <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms form-horizontal">
                  <label><strong><?=_('Date Estabilished') ?>:</strong></label>
                  
                  <select name="month" class="input-small">
                      <?php foreach($months as $k=> $m) {
                            $m = str_replace(".", "", $m);
                            if($listing AND $listing->site_age != 0) {
                                if(date("M", $listing->site_age) == $m) {
                                    echo '<option value="'.$k.'" selected="">'.$m.'</option>';
                                }else{
                                    echo '<option value="'.$k.'">'.$m.'</option>';
                                }
                            }else{
                                echo '<option value="'.$k.'">'.$m.'</option>';   
                            }
                      }
                      ?>
                  </select>
                  <select name="day" class="input-small">
                      <?php
                      for($i = 1; $i<= 31; $i++) 
                      {
                      if($listing AND $listing->site_age != 0) {
                            if(date("j", $listing->site_age) == $i) {
                                echo '<option value="'.$i.'" selected="">'.$i.'</option>';
                            }else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                      }else{
                          echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                      } 
                      ?>
                  </select>
                  <select name="year" class="input-small">
                      <?php
                      for($i = 1990; $i<= date("Y"); $i++) 
                      {
                          if($listing AND $listing->site_age != 0) {
                              if(date("Y", $listing->site_age) == $i) {
                                 echo '<option value="'.$i.'" selected="">'.$i.'</option>';
                              }else{
                                  echo '<option value="'.$i.'">'.$i.'</option>';
                              }
                          }else{
                              echo '<option value="'.$i.'">'.$i.'</option>';
                          }
                      }  
                      ?>
                  </select>
                   
                  
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div>   
                  <?php
              break;
              
              
              case "monetization":
                  ?>
                  <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms form-horizontal">
                  <label><strong><?=_('Monetization Methods') ?>:</strong></label>
                  
                  <input type="checkbox" name="monetization[]" value="Sales of Products or Services" <?php if($listing AND preg_match('/Sales of Products or Services/i', $listing->monetization)) echo 'checked=""'; ?>/> <?=_('Sales of Products or Services') ?><br/>
                  <input type="checkbox" name="monetization[]" value="Affiliate Income" <?php if($listing AND preg_match('/Affiliate Income/i', $listing->monetization)) echo 'checked=""'; ?>/> <?=_('Affiliate Income') ?><br/>
                  <input type="checkbox" name="monetization[]" value="Advertising Sales" <?php if($listing AND preg_match('/Advertising Sales/i', $listing->monetization)) echo 'checked=""'; ?>/> <?=_('Advertising Sales') ?><br/>
                  
                  <br/>
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div> 
                  <?php
              break;
              
              case "unique":
                  ?>
                  <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms form-horizontal">
                  <label><strong><?=_('Is your Design/Content Unique?') ?></strong></label>
                  
                  <input type="radio" name="unique_" value="not unique" <?php if($listing AND $listing->unique_ == 'not unique') echo 'checked=""'; ?>/> <?=_('Not Unique') ?><br/>
                  <input type="radio" name="unique_" value="design" <?php if($listing AND $listing->unique_ == 'design') echo 'checked=""'; ?>/> <?=_('Design is Unique') ?><br/>
                  <input type="radio" name="unique_" value="content" <?php if($listing AND $listing->unique_ == 'content') echo 'checked=""'; ?>/> <?=_('Content is Unique') ?><br/>
                  <input type="radio" name="unique_" value="design & content" <?php if($listing AND $listing->unique_ == 'design & content') echo 'checked=""'; ?>/> <?=_('Both Content &amp; Design are Unique') ?><br/>
                  
                  <br/>
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div> 
                  <?php
              break;
              
            case "payments_accepted":
                ?>
                <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms form-horizontal">
                  <label><strong><?=_('Accepted Payment Methods') ?>:</strong></label>
                  
                  <input type="checkbox" name="payment_options[]" value="Escrow.com" <?php if($listing AND preg_match('/Escrow/i', $listing->payment_options)) echo 'checked=""'; ?>/> Escrow.com<br/>
                  <input type="checkbox" name="payment_options[]" value="Credit Card" <?php if($listing AND preg_match('/Credit Card/i', $listing->payment_options)) echo 'checked=""'; ?>/> Credit Card<br/>
                  <input type="checkbox" name="payment_options[]" value="Cheque" <?php if($listing AND preg_match('/Cheque/i', $listing->payment_options)) echo 'checked=""'; ?>/> Cheque<br/>
                  <input type="checkbox" name="payment_options[]" value="PayPal" <?php if($listing AND preg_match('/PayPal/i', $listing->payment_options)) echo 'checked=""'; ?>/> PayPal<br/>
                  
                  <br/>
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                </form>
                  
                <div class="ajax-modal-result"></div> 
                <?php
            break;
            
            case "revenue":
                ?>
                <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms">
                  <label><strong><?=_('Last three months AVERAGE') ?>:</strong></label>
                  <input type="text" name="rev_avg" value="<?php echo $listing->rev_avg; ?>"/> per month<br/>
                  <br/><br/>
                  <label><strong><?=_('Describe revenue as much as possible') ?>:</strong></label>
                  <textarea name="revenue_details" id="listing_description" rows="8" class="input-xxlarge required" style="width:650px;"><?php echo $listing->revenue_details; ?></textarea>
                  <br/>    
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div>    
                <?php
            break;
            
            case "traffic_details":
                ?>
                <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms">
                  <label><strong><?=_('Last three months AVERAGE visits') ?>:</strong></label>
                  <input type="text" name="traffic_avg_visits" value="<?php echo $listing->traffic_avg_visits; ?>"/> per month<br/>
                  <br/>
                  
                  <label><strong><?=_('Last three months AVERAGE views') ?>:</strong></label>
                  <input type="text" name="traffic_avg_views" value="<?php echo $listing->traffic_avg_views; ?>"/> per month<br/>
                  
                  <br/><br/>
                  
                  <label><strong><?=_('Traffic description') ?>:</strong></label>
                  <textarea name="traffic_details" id="listing_description" rows="8" class="input-xxlarge required" style="width:650px;"><?php echo $listing->traffic_details; ?></textarea>
                  
                  <br/>    
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div>    
                <?php
            break;
            
            case "tags":
                ?>
                
                <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms">
                  <p class="alert alert-warning"><?=_('Only one keyword per tag is allowed.') ?></p>
                  
                  <label><strong><?=_('Niche') ?>:</strong><?=_('(health, sports, etc.)') ?></label>
                  <input type="text" name="tag_niche" value="<?php echo $listing->tag_niche; ?>"/>
                  <br/>
                  
                  <label><strong><?=_('Type') ?>:</strong><?=_('(forum, blog, etc.)') ?></label>
                  <input type="text" name="tag_type" value="<?php echo $listing->tag_type; ?>"/>
                  <br/>
                  
                  <label><strong><?=_('Implementation') ?>:</strong><?=_('(custom, wordpress, etc.)') ?></label>
                  <input type="text" name="tag_implementation" value="<?php echo $listing->tag_implementation; ?>"/>
                  <br/>
                  
                  <br/>    
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                  
                  <div class="ajax-modal-result"></div>
                
                <?php
            break;
            
            case "verify":
				
                ?>
                <h3 class="text-info"><?=_('Upload a file to your host') ?>:</h3>
                <span class="text-info"><?=_('Upload a file called ') ?><span class="text-warning">verify_<?php echo $id ?>.txt</span> 
                <?=_("so it's accessibile on this URL : ") ?><span class="text-warning">http://<?php echo $listing->listing_url; ?>/verify_<?php echo $id ?>.txt</span></span>
                
                <br/>
                
                <a href="/users/verify_file/<?php echo $id; ?>" target="_blank" style="font-weight:bold;color:#cc0000;font-size:16px;"><?=_('Download file') ?></a>
                
                <br/><br/>
                <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="ajax-modal-forms">
                  <br/>    
                  <input type="hidden" name="verify_file" value="<?php echo $id; ?>" />
                  <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
                  </form>
                <div class="ajax-modal-result"></div>
                <?php
            break;
              
              
          }
          
          $this->htmlfooter();
          
          ob_end_flush();
      }

      
      /*
       * AJAX Attachments
       */
       public function att() {
           if(!$this->loggedIn) 
            {
                redirect('/users/login');
                exit;
            }

            $id = $this->session->userdata("listingID");
            $id = abs(intval($id));  
            $userID = $this->loggedIn;
            
            if(!$id) die("Listing ID Not set");
            
            if(!$this->input->post("sb_att")) die("Page reached in error");
            
            $att_title = $this->input->post('att_title');
            
            if(!$att_title or empty($att_title)) die(_("Attachment title please"));
            
            //image upload
            if(isset($_FILES['file'])) {
                
                //get extension
                $ext = explode(".", $_FILES['file']['name']);
                $ext = strtolower(end($ext));
                $rand = md5(uniqid());
                
                if($ext != "png" and $ext != "jpg" and $ext != "jpeg") {
                    echo '<div class="alert alert-danger">' . _("File must be PNG/JPEG ONLY") .'</div>';
                    exit;
                }
                
                if(!@getimagesize($_FILES['file']['tmp_name'])){
                    echo '<div class="alert alert-danger">' . _("Invalid/Corrupt image file. Try another one") .'</div>';
                    exit;
                }
                
                if(move_uploaded_file($_FILES['file']['tmp_name'], getcwd() .'/uploads/' .  $rand . '.' . $ext)) {
                
                //make thumbnail
                $config['image_library'] = 'gd2';
                $config['source_image'] = getcwd() .'/uploads/' .  $rand . '.' . $ext;
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['width']     = 44;
                $config['height']   = 26;
                $config['new_image'] = getcwd() . '/uploads/small-' . $rand . '.' . $ext;
                
                $this->load->library('image_lib', $config); 
                
                $this->image_lib->resize();
                
                if ( ! $this->image_lib->resize())
                {
                    echo $this->image_lib->display_errors();
                    exit;
                }
                
                
                $this->db->insert("attachments", 
                                 array("listID" => $id, 
                                      "att_title" => trim(strip_tags($att_title)), 
                                      "att_file" => $rand . '.' . $ext));
                
                if($this->db->affected_rows()) {                      
                   echo '<script>window.parent.location.reload();</script>';
                }   else{
                    echo $this->db->last_error();
                }                                 
                
                }else{
                    echo _('Image could not be uploaded.');
                }
            
            }else{
                echo _("Please choose a file to be uploaded!");
            }
       }


       /*
        * Remove attachments
        */
        public function remove_att() {
            ob_start();
            
            if(!$this->loggedIn) 
            {
                redirect('/users/login');
                exit;
            }
           
           
           $attID = $this->uri->segment(3);
           $attID = abs(intval($attID));
           $userID = $this->loggedIn;
           
           
           if(!$attID || !$userID) exit(div_class('Error! No Attachment ID / UserID'));
           
           //check if owns this attachments
           $rs = $this->db->get_where("attachments", array("attachID" => $attID));
           $rs = $rs->row();
           
           if(!count($rs)) die("No att with this id");
           
           $rs = $this->db->query("select list_uID from listings where listingID = '$rs->listID'");
           $u = $rs->row();
           
                      
           if(!count($u)) die("could not get list owner info");
           
           if($u->list_uID != $userID) die("You dont own this listing");
           
           $this->db->delete("attachments", array("attachID" => $attID));
           
           header("Location: /users/newlisting");
           
            
           ob_end_flush();
        }
        
        

      /*
       * AJAX Listing Insert/Update
       */
       public function updatelistings() {
           if(!$this->loggedIn) 
            {
                redirect('/users/login');
                die("Not logged in");
            }
           
           
           $listingID = $this->uri->segment(3);
           $listingID = abs(intval($listingID));
           $userID = $this->loggedIn;
           
           
           if(!$listingID || !$userID) exit(div_class('Error! No ID / UserID'));
           
           
           foreach($_POST as $k => $v) {
               
               if(!is_array($v)) {
                   if($k != "listing_description" AND $k != "revenue_details" AND $k != "traffic_details") {
                      $_POST[$k] = trim(strip_tags($v));
                   }elseif($k == "listing_description"){
                      $_POST[$k] = trim(strip_tags($v, "<i><em><p><br><ol><ul><li><b><strong><h1><h2><h3><h4><h5><h6><font><span><div>")); 
                   }
                   
                   if($k == "tag_niche" || $k == "tag_implementation" || $k == "tag_type") {
                       $_POST[$k] = str_replace(array('"', "'"), array("", ""), $v);
                       $_POST[$k] = preg_replace('/[^,]*,\s*/', "", $v);
                   }
                   
                   if(strlen($v) == 0) {
                       echo div_class(_("All fields are required. If you see this in error hit Submit again.") . " " . $k);
                        exit;
                   }
                   
                   
                   //validate numbers
                   if($k == 'reserve' || $k == 'bin' || $k == 'starting_') {
                       $v = abs(intval($v));
                       
                       if($v < 10) {
                           echo div_class(_("BIN/Starting/Reserve must be at least 10"));
                           exit;
                       }
                       
                   }
               }
               
           }//foreach
           //validate date estabilished
           if($this->input->post('month') && $this->input->post('day') && $this->input->post('year')) {
              $date = mktime(0,0,0,$this->input->post('month'),$this->input->post('day'), $this->input->post('year'));
              $_POST['site_age'] = $date;
           }
           
           
           //monetization serialize (if set)
           if($this->input->post('monetization')) {
               if(!empty($_POST['monetization']) AND isset($_POST['monetization'])) {
                    $_POST['monetization'] = serialize($_POST['monetization']);
               }
           }
           
           //payment methods serialize (if set)
           if($this->input->post('payment_options')) {
               if(!empty($_POST['payment_options']) AND isset($_POST['payment_options'])) {
                    $_POST['payment_options'] = serialize($_POST['payment_options']);
               }
           }
           
           //update listing
           if(isset($_POST[0])) unset($_POST[0]);
           if(isset($_POST['year'])) unset($_POST['year']);
           if(isset($_POST['month'])) unset($_POST['month']);
           if(isset($_POST['day'])) unset($_POST['day']);
           
           unset($_POST['sb']);
           
           
           //verify file
           if($this->input->post('verify_file')) {
               
               $uri = $this->db->get_where("listings", array("listingID" => $listingID, 'list_uID' => $userID));
               
               if($uri->num_rows()) {
                   $uri = $uri->row();
                   
                   //try reading the file
                   $file = 'http://' . $uri->listing_url . '/verify_' . $listingID . '.txt';
                   
                    $ch = curl_init();
                    $timeout = 5;
                    curl_setopt($ch, CURLOPT_URL, $file);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $contents = curl_exec($ch);
                    curl_close($ch);

                   if ($contents) {
                       if($contents != md5('verify-' . $listingID)) {
                           echo div_class("Error : File doesn't contain the validation code");
                           exit;
                       }else{
                           $_POST['verified'] = 'Y';
                           unset($_POST['verify_file']);
                       }
                   }
                   
               }
           }
           
           
           
           if($this->input->post()) {
              $this->db->update("listings", $this->input->post(), array("listingID" => $listingID, 'list_uID' => $userID));
               echo '<div class="alert alert-success">Successfully saved.</div>';
           }else{
               echo div_class(_("Nothing to be saved."));
           }          
       }

      /*
       * HTML Headers -- for iframe forms
       */
      public function htmlheader() {
          ?>
          <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Website Marketplace</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href='https://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css' />
                <link href='https://fonts.googleapis.com/css?family=Cabin:400' rel='stylesheet' type='text/css'>
                <link href="<?php echo base_url(); ?>css/bootstrap.css" type="text/css" rel="stylesheet" />
                <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css" />
                <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
                <script src="<?php echo base_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>js/ajax.js"></script>
                <script src="<?php echo base_url(); ?>js/nicEdit.js" type="text/javascript"></script>
                <script type="text/javascript">
                bkLib.onDomLoaded(function() {
                    new nicEditor({iconsPath : '<?php echo base_url(); ?>img/nicEditorIcons.gif', maxHeight : 400, buttonList : ['forecolor', 'fontFormat','bold','italic','underline','strikeThrough','subscript','superscript', 'ol', 'ul', 'left', 'center', 'right']}).panelInstance('listing_description');
                });
                </script>    
                <!--[if gte IE 9]>
                  <style type="text/css">
                    .gradient {
                       filter: none;
                    }
                  </style>
                <![endif]-->
            </head>
            <body style="background:white;">
            <div style="margin-top:15px;margin-left:15px;">
          <?php
      }
        
        
        /*
         * HTML Footer -- for iframe forms
         */
        public function htmlfooter() {
            echo '</div></body></html>';
        }
        
        
        /*
         * Verify file generate
         */
	    public function verify_file() {
	        ob_start();
	        if(!$this->loggedIn) 
            {
                redirect('/users/login');
                exit;
            }
            
            $id = $this->uri->segment(3);
            $id = abs(intval($id));
            
            if(!$id) die("Invalid Listing ID");
            
            header('Content-type: text/plain');
            header('Content-Disposition: attachment; filename="verify_'.$id.'.txt"');
            
            echo md5('verify-' . $id);

            ob_end_flush();
	    }
}