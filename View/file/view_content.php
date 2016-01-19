<?php
    $result = array();
        foreach ($this->list as $file) {
            $result[] = array(
                'id' => $file['id'],
                'file_name' => $file['file_name'],
                'description' => $file['description'],
                'tag' => "<div class='badge badge-success'>" . join("</div> <div class='badge badge-success'>", split(",+", $file['tag'])) . "</div>",
                'category_name' => $file['category_name'],
                'create_date' => date("d M Y @ H:m:s", strtotime($file["create_date"])) ." ". "by" ." ". $file['username'] ,
                'create_user_id' => $file['username'],
                'memo' => $file['memo'],
                'action' => '<div class="btn-group pull-right">
                                 <button type="button" onclick="showModalView('.$file['id'].')" class="btn btn-success btn-xs">View</button>
                                <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="javascript:void(0)" title="Edit" onclick="showModals('.$file['id'].')" >
                                            <i class="glyphicon glyphicon-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="javascript:void(0)" title="Edit" onclick="deleteData('."'".$file['id']."'".')">
                                            <i class="glyphicon glyphicon-trash"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>',
            );
        }
    echo json_encode(array('files'=>$result));
?>