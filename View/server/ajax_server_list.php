<?php $client_list = $this->client_list; ?>
<div class="alert"></div>
<button class="button button-gray button-small" data-toggle="modal" data-target="#modal-new-client">New Client</button>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Client hash id</th>
                <th>Client name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php
$i=1;
        foreach ($client_list as $row) {
?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $row['client_hash_id'] ?></td>
                <td><?= $row['client_name'] ?></td>
                <td>
<!--                        <button class="button button-blue button-xsmall">Edit</button>                        
                    <button class="button button-green button-xsmall">Change Password</button>-->
                    <button class="button button-red button-xsmall" data-action="delete" data-id="<?= $row['client_hash_id'] ?>">Delete</button>
                </td>
            </tr>
<?php
$i++;
        }
?>            
        </tbody>
    </table>
</div>

<div class="modal-wraper" id="modal-new-client">
    <div class="modal-container">        
        <div class="modal-header">
            <h1>New client</h1>
            <!--<span class="modal-close">&times;</span>-->            
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Client name</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="client_name" class="form-text full-width" id="client_name"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>Password</label>
                </div>
                <div class="col-md-9">
                    <input type="password" name="password" class="form-text full-width" id="client_password"/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-md-9 col-md-offset-3">
                    <button class="button button-blue button-small full-width" id="save_client" onclick="save_client($('#client_name').val(),$('#client_password').val())">Save</button>
                    <button class="button button-gray button-small full-width" data-type="dismiss">Close</button>
                </div>
            </div>
        </div>        
    </div>
</div>