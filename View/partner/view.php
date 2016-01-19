<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<h2><?= $this->title ?></h2>

This is view's viewer'
<br>
<br>
The result 1 is <?= $this->result_1 ?>
<br>
The result 2 is 
    <?php 
        $result2 = $this->result_2;
        
        if($result2 < 10) {
            ?>
            <span class="text-danger"><?= $result2 ?></span>
        <?php
        } else {
            ?>
            <span class="text-success"><?= $result2 ?></span>
        <?php
        }
            
    ?>

            
            <br><br><br>
            
Now displaying partner list

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Birthday</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($this->partner_list as $p){
            ?>
        <tr>
            <td><?= $p["name"] ?></td>
            <td><?= str_replace("\n", "<br>", $p["address"]) ?></td>
            <td><?= date("d-M-Y", strtotime($p["birthday"])) ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<br><br>

Display Partner 
<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Birthday</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($this->partner as $l){
            ?>
        <tr>
            <td><?= $l["name"] ?></td>
            <td><?= str_replace("\n", "<br>", $l["address"]) ?></td>
            <td><?= date("d-M-Y", strtotime($l["birthday"])) ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
