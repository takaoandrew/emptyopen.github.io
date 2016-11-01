<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function index()
	{
	    $this->load->model('Listings');
        
	    $data = array();

        $listings = $this->db->query("SELECT listingID, listing_title, listing_url, bin, 
	    									CONCAT('$', FORMAT(`bin`, 0)) as `starting_`, site_age, `starting_` as starting_bid, 
	    									CONCAT('$', FORMAT(rev_avg, 0)) as rev_avg, list_date, list_expires, 
	    									FORMAT(traffic_avg_visits, 0) as traffic_avg_visits, pagerank, 
	    									PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'), FROM_UNIXTIME(site_age, '%Y%m')) AS diff 

	    									FROM (`listings`) 

	    									WHERE `listing_status` = 'active' 
	    									AND `sold` = 'N' 
	    									AND `featured` = 'Y' 
	    									AND (list_type = 'website' OR list_type = 'domain') 
	    									ORDER BY `listingID` DESC LIMIT 10");

        $data['listings'] = $listings->result();
         
		$this->load->view('home', $data);
	}


	public function tos() {
		$this->load->model("UsersModel");
		
		$tos = $this->db->get("tos", 1);
		$data['tos'] = $tos->row();
		$data['seo_title'] = 'Terms of Service  -  ' . get_option('seo_title');

		$this->load->view('tos', $data);
	}


	public function contact() {
		$this->load->model('UsersModel');

		$data = array();
		$data['seo_title'] = 'Contact  -  ' . get_option('seo_title');
		$this->load->view('contact', $data);
	}

	public function contactajax() {

		foreach($_POST as $k => $v) {
			$_POST[$k] = trim(strip_tags($v));
			if(empty($_POST[$k])) die('All fields are required');
		}

		$body = 'From: ' . $_POST['yname'];
		$body .= "\r\n";

		$body .= 'Email: ' . $_POST['yemail'];
		$body .= "\r\n";

		$body .= 'Subject: ' . $_POST['ysubject'];
		$body .= "\r\n";

		$body .= 'Message: ' . nl2br(str_replace("<br>", "\n\r", $_POST['ymessage']));

		if(mail(get_option('contact_email'), 'Contact Form', $body)) {
			echo '<div class="alert alert-success">Thanks for contacting us! We will get back to you soon.</div>';
			echo '<script>$("#contact-form").hide();</script>';
		}


	}


	public function searchautocomplete() {

		$q = $this->uri->segment(3);
		if(!$q) die();

		$string = trim(strip_tags($q));
		$db_string = urldecode($string);
		
		$this->db->select("listingID, listing_title, listing_url, listing_status");
		$this->db->like("listing_title", $db_string);
		$this->db->or_like("listing_url", $db_string);
        
		$listings = $this->db->get('listings', 10);


		if(!count($listings->result())) {
			die('No results');
		}

		?>
		
		<ul class="playlist">
		<?php 
		foreach($listings->result() as $m) : 
			if($m->listing_status != 'active' OR empty($m->listing_title)) continue;
		?>
		<li>
			<hr>					
			
			<a href="<?php echo '/listings/'.$m->listingID.'/'.url_title($m->listing_title); ?>" class="url-listing-title" style="font-size:14px;">
			<i class="icon icon-tag"></i> <?php echo $m->listing_url; ?>
			</a>	
			<br />	
			<a href="<?php echo '/listings/'.$m->listingID.'/'.url_title($m->listing_title); ?>">
			<small><?php echo $m->listing_title; ?></small>
			</a>
		</li>
		<?php endforeach; ?>
		<li>&nbsp;</li>
		</ul>

		<?php 

	}


	public function lostpassword() {

		$data = array( 'msg' => '');

		if($e = $this->input->post('ea')) {
			if(filter_var($e, FILTER_VALIDATE_EMAIL)) {

				// get this user details from db
				$query = $this->db->query("SELECT userID, username, email FROM users WHERE email = ? LIMIT 1", array($e));

				if ($query->num_rows() > 0)
				{
				   $row = $query->row(); 
				   
				   $hash = md5($row->userID.$row->email);
				   $to = $row->email;
				   $subject = 'Password Reset Email';

				   $body = 'Hi there <srong>'.$row->username.'</strong>,<br/><br/>';

				   $body .= 'You have requested a password reset email:<br/>';
				   $body .= '<a href="'.base_url().'home/resetpwd?hash='.$hash.'">'.base_url().'/home/resetpwd?hash='.$hash.'</a>';

				   $body .= '<br/><br/>Ignore if it wasn\'t you to request this password reset email!';

				   // To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				    mail($to, $subject, $body, $headers);

				    $data['msg'] = '<div class="alert alert-success">Please check your inbox/spambox for the reset link!</div>';
				  

				}else{
					$data['msg'] = '<div class="alert alert-danger">No such email in database.</div>';
				}

			}else{
				$data['msg'] = '<div class="alert alert-danger">Invalid email.</div>';
			}
		}

		$this->load->view('lost-password', $data);

	}


	public function resetpwd() {

		if($hash = $this->input->get('hash')) {

			$data['msg'] = '';

			$hash = trim(strip_tags($hash));

			// get this user details from db
			$query = $this->db->query("SELECT userID FROM users WHERE MD5(CONCAT(userID, email)) = ? LIMIT 1", array($hash));
			if ($query->num_rows() > 0)
			{
				$row = $query->row();

				if($new_pwd = $this->input->post('pn')) {

					if(empty($new_pwd)) die("No empty password allowed");

					$reset = $this->db->query("UPDATE users SET password = MD5(?) WHERE userID = ?", array($new_pwd, $row->userID));
					if ($this->db->affected_rows()) {
						$data['msg'] = '<div class="alert alert-success">Successfully reset password. You may now login with the new credentials!</div>';
					}

				}

			}else{
				die("Invalid hash!");
			}


			$this->load->view('reset-password', $data);

		}else{
			$this->load->view('404');
		}

	}

	
}