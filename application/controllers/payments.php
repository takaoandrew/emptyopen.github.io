<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payments extends CI_Controller {

    public $loggedIn;
    public $listingID;
    public $listing;

    /*
     * Check if logged in or not and assign it to all methods
     */
    function __construct() {
        parent::__construct();
        
        
    }
    
    public function setfeatured() {
        $id = $this->uri->segment(3);
        $id = abs(intval($id));
        
        if(!$id) die("Error. List id not correct");
        
        $this->session->set_userdata('listingID', $id);
        redirect('/payments/featured');
    }
    
    public function relist() {
        $id = $this->uri->segment(3);
        $id = abs(intval($id));
        
        if(!$id) die("Error. List id not correct");
        
        $this->session->set_userdata('listingID', $id);


        // if it's free
        $listingID = $this->session->userdata('listingID');

        // check if listing is free
        if(get_option('listing_fee') == 0) {
            $this->db->update("listings", 
                                array("list_expires" => strtotime("+1 Month"), 
                                     "listing_status" => "active"), 
                                array("listingID" => $listingID));

            echo '<meta http-equiv="refresh" content="0; url= /users/mylistings?added=success">';
            exit;

        }


        // if not show payment options
        $this->load->view("relist", array('listingID' => $listingID));

        //redirect('/payments/index');
    }

    // stripe payment LISTING FEE
    public function stripe() {

        if(isset($_POST['stripeToken']) AND !empty($_POST['stripeToken']) AND isset($_POST['listingID'])) {

            $listingID = intval($_POST['listingID']);

            $stripe_token = trim(strip_tags($_POST['stripeToken']));

            $post_params = array('amount' => get_option('listing_fee')*100, 
                                'currency' => 'usd', 
                                'source' => $stripe_token, 
                                'description' => 'Listing fee #' . $listingID);

            //url-ify the data for the POST
            $fields_string = '';
            foreach($post_params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
            
            $ch = curl_init('https://api.stripe.com/v1/charges');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, get_option('stripe_private'));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_POST, count($post_params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

           $output = curl_exec($ch);
           $info = curl_getinfo($ch);

           curl_close($ch);

           $output = json_decode($output);

           if(!$output) die("Could not decode stripe json return");


           if(isset($output->error)) {
                die("Stripe error: " . $output->error->message );
           }else{

                if(isset($output->status) AND ($output->status == 'succeeded')) {
                    // set this $listingID as featured 

                    $this->db->update("listings", 
                                        array("list_expires" => strtotime("+1 Month"), 
                                             "listing_status" => "active"), 
                                        array("listingID" => $listingID));

                    echo '<meta http-equiv="refresh" content="0; url=/users/mylistings?added=true "/>';

                }else{

                    echo "Sripe payment failed<br/>";
                    echo $output->failure_message;

                }

           }

        }

    }

     // stripe payment FEATURED
    public function stripefeatured() {

        if(isset($_POST['stripeToken']) AND !empty($_POST['stripeToken']) AND isset($_POST['listingID'])) {

            $listingID = intval($_POST['listingID']);

            $stripe_token = trim(strip_tags($_POST['stripeToken']));

            $post_params = array('amount' => get_option('featured_fee')*100, 
                                'currency' => 'usd', 
                                'source' => $stripe_token, 
                                'description' => 'Featured listing #' . $listingID);

            //url-ify the data for the POST
            $fields_string = '';
            foreach($post_params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
            
            $ch = curl_init('https://api.stripe.com/v1/charges');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, get_option('stripe_private'));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_POST, count($post_params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

           $output = curl_exec($ch);
           $info = curl_getinfo($ch);

           curl_close($ch);

           $output = json_decode($output);

           if(!$output) die("Could not decode stripe json return");


           if(isset($output->error)) {
                die("Stripe error: " . $output->error->message );
           }else{

                if(isset($output->status) AND ($output->status == 'succeeded')) {
                    // set this $listingID as featured 

                    $this->db->update("listings", 
                                    array("featured" => "Y"), 
                                    array("listingID" => $listingID));

                    echo '<meta http-equiv="refresh" content="0; url=/users/mylistings?added_featured=true "/>';

                }else{

                    echo "Sripe payment failed<br/>";
                    echo $output->failure_message;

                }

           }

        }

    }

    public function index() {
        $this->load->model('UsersModel');
        $data['viewdata'] = '';
        
        //if(empty($l->listing_url) || empty($l->listing_title))
        //{
            //$this->load->view('header');
            //echo _("Please review the completion percentage before trying to publish your listing.");
            //exit;
        //}

        
        $this->load->library('CRV_PayPalClass');

        switch ($this->uri->segment(4)) {

            default :
                
                $settings = site_settings();
            	$loggedIn = $this -> session -> userdata('loggedIn');
       	        $listingID = $this -> session -> userdata('listingID');
                $listing = $this -> db -> get_where("listings", array("listingID" => $this -> listingID));

                // check if listing is free
                if(get_option('listing_fee') == 0) {
                    $this->db->update("listings", 
                                        array("list_expires" => strtotime("+1 Month"), 
                                             "listing_status" => "active"), 
                                        array("listingID" => $listingID));

                    echo '<meta http-equiv="refresh" content="0; url= /users/mylistings?added=success">';
                    exit;

                }
                
                // setup a current URL variable for this script
                $this_script = 'http://' . $_SERVER['HTTP_HOST'] . '/payments/index';
                
                ob_start();
                CRV_PayPalClass::add_field('business', get_option('paypal_email'));
                CRV_PayPalClass::add_field('return', $this_script . '/action/success');
                CRV_PayPalClass::add_field('cancel_return', $this_script . '/action/cancel');
                CRV_PayPalClass::add_field('notify_url', $this_script . '/action/ipn');
                CRV_PayPalClass::add_field('item_name', 'Listing Fee');
                CRV_PayPalClass::add_field('amount', get_option('listing_fee'));
                CRV_PayPalClass::add_field('currency_code', 'USD');
                CRV_PayPalClass::add_field('custom', $listingID);
                CRV_PayPalClass::add_field('cmd', '_xclick');
                CRV_PayPalClass::add_field('rm', '2');

                CRV_PayPalClass::submit_paypal_post();
                // submit the fields to paypal
                $data['viewdata'] = ob_get_clean();

                break;

            case 'success' :
                
                redirect('/users/mylistings');    
                
            break;

            case 'cancel' :
                
                $this->load->view('header');
                
                $data['viewdata'] = _('Canceled listing');
                
                break;

            case 'ipn' :

                $hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
                if (! preg_match ( '/paypal\.com$/', $hostname )) {
                    error_log('Validation post isn\'t from PayPal ' . $hostname);
                    exit;
                }

                $body = '';

                if(isset($_POST['payment_status']) AND isset($_POST['txn_type']) AND isset($_POST['custom'])) {

                    if($_POST['payment_status'] == 'Completed') {
                    
                        $listingID = abs(intval($_POST['custom']));
                        $this->db->update("listings", 
                                        array("list_expires" => strtotime("+1 Month"), 
                                             "listing_status" => "active"), 
                                        array("listingID" => $listingID));
                    }

                }

                break;
        }

        $this->load->view('paypal', $data);
    }


    public function featured() {
        $this->load->library('CRV_PayPalClass');
        $this->load->model('UsersModel');

        $data['viewdata'] = '';

        switch ($this->uri->segment(4)) {

            default :
            
            	$loggedIn = $this -> session -> userdata('loggedIn');
	            $listingID = $this -> session -> userdata('listingID');
	            $listing = $this -> db -> get_where("listings", array("listingID" => $this -> listingID));
                
                // setup a current URL variable for this script
                $this_script = 'http://' . $_SERVER['HTTP_HOST'] . '/payments/featured';
                $settings = site_settings();
                
                    ob_start();
                    CRV_PayPalClass::add_field('business',  get_option('paypal_email'));
                    CRV_PayPalClass::add_field('return', $this_script . '/action/success');
                    CRV_PayPalClass::add_field('cancel_return', $this_script . '/action/cancel');
                    CRV_PayPalClass::add_field('notify_url', $this_script . '/action/ipn');
                    CRV_PayPalClass::add_field('item_name', 'Featured Fee');
                    CRV_PayPalClass::add_field('amount', get_option('featured_fee'));
                    CRV_PayPalClass::add_field('currency_code', 'USD');
                    CRV_PayPalClass::add_field('custom', $listingID);
                    CRV_PayPalClass::add_field('cmd', '_xclick');
                    CRV_PayPalClass::add_field('rm', '2');

                    CRV_PayPalClass::submit_paypal_post();


                    $data['viewdata'] = ob_get_clean();

                // submit the fields to paypal
                break;

            case 'success' :
                
                redirect('/users/mylistings');    
                
            break;

            case 'cancel' :
                
                $this->load->view('header');
                
                $data['viewdata'] = _('Canceled listing');
                
                break;

            case 'ipn' :
                
                $hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
                if (! preg_match ( '/paypal\.com$/', $hostname )) {
                    error_log('Validation post isn\'t from PayPal ' . $hostname);
                    exit;
                }

                $body = '';

                if(isset($_POST['payment_status']) AND isset($_POST['txn_type']) AND isset($_POST['custom'])) {

                    if($_POST['payment_status'] == 'Completed') {
                    
                        $listingID = abs(intval($_POST['custom']));
                        $this->db->update("listings", 
                                    array("featured" => "Y"), 
                                    array("listingID" => $listingID));

                    }

                }

                break;

        }

        $this->load->view('paypal', $data);
    }

}