<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($this->file_list_data as $p){
        }
            ?>
        <p><?= $p["name"] ?></p>
        <tr>
            <td><?= $p["name"] ?></td>
            <td><?= $p["email"] ?></td>
            <td><?= $p["email"] ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>