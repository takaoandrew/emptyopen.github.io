<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings extends CI_Controller {

    function __construct() {
        parent::__construct();      
        $this->load->model("UsersModel");
    }
    
    /*
     * Biding page
     */
     public function bid() {
         $userid = $this->session->userdata("loggedIn");
         if(!$userid) header("Location: /users/login");
         
         $listingID = $this->uri->segment(3);
         $listingID = abs(intval($listingID));
         
         if(!$listingID) die(_("Invalid listing ID"));
         
         $data['listingID'] = $listingID;
         
         $message = '';
         
         
         
         //if bid
         if($this->input->post('sb_bid')) {
         
             $bid_amount = $this->input->post('bid_amount');
             $bid_amount = abs(intval($bid_amount));
             if(!$bid_amount || empty($bid_amount) || $bid_amount < 0) $message = _("Bid amount required");
             
             $data['bid_amount'] = $bid_amount;
             
             //check that bid amount is at least +5 higher than last bid or starting price
             
             $this->db->select("amount")->from("bids")->where("bid_listing", $listingID)->order_by('bidID DESC')->limit(1);
             $rs = $this->db->get();
             
             if($rs->num_rows()) {
                 $min_bid = $rs->row()->amount + 5;
             }else{
                 $this->db->select("starting_")->from("listings")->where("listingID", $listingID);
                 $min_bid = $this->db->get();
                 if($min_bid->num_rows()) {
                     $min_bid = $min_bid->row()->starting_+5;
                 }else{
                     $message = _('Could not find a starting bid for this listing.');
                 }
             }

             if($min_bid > $bid_amount) {
                 $message = _('This bid should be at least of') . ' $' . number_format($min_bid, 0);
             }else{
                 if($this->uri->segment(4) && $this->uri->segment(4) == 'confirm') {
                     
                     $rs = $this->db->query("SELECT list_uID FROM listings WHERE listingID = ?", array($listingID));
                     $ownerID = $rs->row()->list_uID;
                     
                     if($rs->row()->list_uID == $userid) {
                             
                         $message = _('Do not bid on your own listings');
                         
                     }else{
                     
                         $bidArray = array('bid_date' => time(), 'bid_listing' => $listingID, 
                                           'bidder_ID' => $userid, 'owner_ID' => $ownerID,
                                           'amount' => $bid_amount);
                         if($this->db->insert("bids", $bidArray)) {
                            $message = _('Bid confirmed, thank you.');
                             #$message .= '<br/>' . $this->db->last_query();

                            // email the listing owner 
                            $headers  = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                            $query = $this->db->query("SELECT username, email FROM users WHERE userID = ? LIMIT 1", array($ownerID));
                            $user_data = $query->row();

                            $to = $user_data->email;

                            $body = 'Hi there <srong>'.$user_data->username.'</strong>,<br/><br/>';

                           $body .= 'You have received a new bid of $'.$bid_amount.' to your listing:<br/>';
                           $body .= '<a href="'.base_url().'listings/'.$listingID.'/new-bid-received">'.base_url().'/'.$listingID.'/new-bid-received</a>';

                           $body .= '<br/><br/>Please login to view full bid details!';

                           mail($to, "New Bid Received", $body, $headers);

                         }else{
                             $message = _('DB Error!');
                         }
                     }
                 }
             }
   
         }
         
         //if bin
         elseif($this->input->post('sb_bin')) {
             $this->db->select("bin")->from("listings")->where("listingID", $listingID);
             $min_bid = $this->db->get();
             if($min_bid->num_rows()) {
                 $min_bid = $min_bid->row()->bin;
                 $data['bid_amount'] = $min_bid;
             }else{
                 $message = _('Could not find a starting bid for this listing.');
             }
         }
         
         
         else{
             $message = _("Bid or bin required to view this page");
         }
         
         $data['message'] = $message;
         $this->load->view('bid', $data);
         
     }
    
    
    /*
     * Listing details page
     */
    public function index()
    {
        $userid = $this->session->userdata("loggedIn");
        
        $this->load->helper("form");
        
        $listingID = $this->uri->segment(2);
        $listingID = abs(intval($listingID));
        
        if(!$listingID) {
            $this->load->view('listing-not-found');
        }else{
         
            //get listing details
            $this->db->select("listings.*, userID, username, photo");
            $this->db->from("listings");
            $this->db->where(array("listingID" => $listingID));
            $this->db->join("users", "listings.list_uID = users.userID");
            $l = $this->db->get();
            
            
            if($l->row('listing_status') != "active"):
            
                $this->load->view('inactive-listing');
            
            else: 

            if($l->num_rows()) {
                
                //get latest bid
                $last_bid = $this->db->query("(SELECT amount FROM bids WHERE bid_listing = $listingID 
                                  ORDER BY bidID DESC LIMIT 1)
                                  UNION
                                  (SELECT COUNT(bidID) as t_Bids FROM bids WHERE bid_listing = $listingID LIMIT 1)");
                $last_bid = $last_bid->result();
                
                if(count($last_bid) and $last_bid[0]->amount > 0) {
                    $bids_count = abs($last_bid[1]->amount);
                    $last_bid_plus = '$' . number_format($last_bid[0]->amount+5, 0);
                    $lstatus = ($last_bid[0]->amount >= $l->row()->reserve) ? _('Reserve Met') : _('Reserve Not Met');
                    if($last_bid[0]->amount >= $l->row()->bin) { $lstatus = _('Received BIN'); }
                    $last_bid = '$' . number_format($last_bid[0]->amount, 0);
                }else{
                    $last_bid = '$' . number_format($l->row()->starting_, 0);
                    $last_bid_plus = '$' . number_format($l->row()->starting_+5, 0);
                    $bids_count = 0;  
                    $lstatus = _('Reserve Not Met');
                }
                
                if($l->row()->sold == 'Y') {
                    $data['hide_bid'] = 'true';
                    $lstatus = _('Sold on ');
                    $lstatus .= date("jS F Y", $l->row()->sold_date); 
                }
                
                //get comments
                $this->db->select("commID, comment, comm_date, userID, username");
                $this->db->from("comments");
                $this->db->where("listID = $listingID");
                $this->db->join('users', 'comments.commUser = users.userID');
                $comments = $this->db->get();
                
                $data['last_bid'] = $last_bid;
                $data['bid_count'] = $bids_count;
                $data['last_bid_plus'] = $last_bid_plus;
                $data['lstatus'] = $lstatus;
                $data['owns_listing'] = ($l->row()->list_uID == $userid) ? 'yes' : 'no';
                
                $data['l'] = $l->row();
                
                if($comments->num_rows()) {
                    $data['comments'] = $comments;
                }else{
                    #$data['comments'] = ;
                }
                
                //get attachments 
                $att = $this->db->get_where("attachments", array("listID" => $listingID));
                $data['att'] = $att->result();
                
                
                $this->load->view('single-listing', $data);
            }else{
                $this->load->view('listing-not-found');    
            }

            endif;
            
        }       
    }

    /*
     * Leave comments to movies
     */
    public function ajax_comment() {
            
        $userID = is_user_logged_in();
        
        if($userID) {
            
            foreach($this->input->post() as $k=>$v) {
                if($this->input->post($k, TRUE) == "") {
                    print '<div class="alert alert-warning">';
                    print _('All fields are mandatory');
                    print '</div>';
                    exit;
                }
            }
            
            $comment = array();
            $comment['comm_date'] = time();
            $comment['commUser'] = $userID;
            $comment['listID'] = abs(intval($this->input->post('listID', TRUE)));
            $comment['comment'] = trim(strip_tags($this->input->post('comment', TRUE)));
            
            
            if(strlen($comment['comment']) < 10 ) {
                echo div_class(_('Please enter at least 10 characters for your comment'), 'alert alert-error');
                exit;
            }
            
            if($this->db->insert("comments", $comment)) {
                echo div_class(_('Thank you for your comment'), 'alert alert-warning');
                echo '<script type="text/javascript">';
                echo '$(function() {';
                    echo '$("#comment-form").hide("slow");';
                echo '})';
                echo '</script>';
            }else{
                echo div_class('DB Error!', "alert alert-error");
            }
            
        }else{
            echo '<div class="alert alert-error">Please login</div>';
        }
    }

    /*
     * Load latest comment via ajax
     */
     function ajax_last_comment() {
         $lastID = abs(intval($this->input->post("last", TRUE)));
         $movID = abs(intval($this->input->post("movie", TRUE)));
         
         if($lastID AND $movID) {
            
            //get comments
            $this->db->select("commID, comment, comm_date, userID, username");
            $this->db->from("comments");
            $this->db->where("commID > $lastID");
            $this->db->where("listID = $movID");
            $this->db->join('users', 'comments.commUser = users.userID');
            $comments = $this->db->get();
            $comments = $comments->result();
            
            if(count($comments)) {
                foreach($comments as $c) {
                    echo '<li data-lastID="'.$c->commID.'">';
                    ?>
                    <span class="comment_author"><b class="icon-user"></b> <?php echo anchor('users/profile/'.url_title($c->username), $c->username); ?> on <b class="icon-calendar"></b><em><?php echo date("jS F Y H:ia", $c->comm_date); ?></em></span>
                    <div class="comment_content"><?php echo wordwrap($c->comment, 80, '<br/>', TRUE); ?></div>
                    <?php
                    echo '</li>';
                }
            }
            
         }else{
            
         }
         
     }

    /*
     * Remove a comment
     */
     function remove_c() {
         $id = $this->uri->segment(3);
         $id = abs(intval($id));
         
         if(!$id) die("ID?");
         
         $userID = $this->session->userdata("loggedIn");
         $userID = abs(intval($userID));
         
         if(!$userID) die("Login first");
         
         $listID = $this->uri->segment(4);
         $listID = abs(intval($listID));
         
         if(!$listID) die("Listing ID?");
         
         $ownsListing = $this->db->get_where("listings", array("listingID" => $listID, "list_uID" => $userID));
         
         #echo $this->db->last_query() . '<br/>';
         
         if($ownsListing->num_rows()) {
             $this->db->delete("comments", array("commID" => $id, "listID" => $listID));
             echo "ok";
         }else{
             die("You dont own the listing");
         }
         
     }
}