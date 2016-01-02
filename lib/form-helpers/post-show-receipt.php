<?php
function show_receipt($ty = "", $success_obj = null){
	if ($success_obj && $success_obj['error']){
		/* == partial to show error messages == */
		print sprintf('<h4>There was a problem with your payment, you were not charged.</h4>
        <div class="alert alert-warning stripe-donate--error">%s</div>',
			$success_obj['error']
		);
	} elseif ($success_obj && $success_obj['success']){
		/* == partial to show receipt == */
		print sprintf('<div class="stripe-donate--receipt"><h3>%s</h3><p>%s</p></div>',
			$success_obj['success'],
      $ty
		);
		echo successModal($success_obj['success'], $ty ,array(
			"href" => site_url(),
			"title" => "Go back to Uplift"
		));
	}
}

function successModal($successMessage = "Successful payment", $body = "", Array $link = null){
	// TODO: There should be a success message and "cause" sent as part of setup
	
    $link = $link ? '<a class="btn btn-lg btn-success" href="'.$link["href"] .'">'.$link["title"].'</a>' : "";
    
	return <<<HTML
<div class="modal fade" id="success-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">$successMessage</h4>
      </div>
      <div class="modal-body">
        $body
      </div>
      <div class="modal-footer">
        $link
      </div>
    </div>
  </div>
</div>
HTML;
}
