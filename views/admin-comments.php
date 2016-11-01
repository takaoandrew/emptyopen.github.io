<?php
require_once ('header.php');
?>


	<div class="page-header white-content">
		<h1>Admin -> Comments</h1>

		<?php require_once 'admin-menu.php'; ?>

	</div>

	<div class="white-content">
		<?php if(count($comments)) : ?>
		<div class="alert alert-warning"><?php echo count($comments) . ' comment(s) to moderate'; ?></div>
		
		<div class="table-responsive">
		<table class="table table-bordered table-striped" id="dataTbl">
			<thead>
				<tr>
					<th>Listing</th>
					<th>Comment</th>
					<th>Date</th>
					<th>User</th>
					<th>Remove</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($comments as $m) : ?>
			
				<tr>
					<td><?=anchor('/listings/'.$m->listingID.'/'.url_title($m->listing_title), $m->listing_url, array('target' => '_blank')); ?></td>
					<td><?=$m->comment?></td>
					<td><?=date('jS F Y', $m->comm_date)?></td>
					<td><?=$m->username.'<br/>'.long2ip($m->ip)?></td>
					<td><a href="/admin/comments/remove/<?=$m->commID;?>"><b class="glyphicon glyphicon-remove"></b></a></td>
				</tr>
			
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>
		
		<?php else: ?>
			
			- no comments -
		
		<?php endif; ?>
	</div>


<?php
	require_once ('footer.php');
?>