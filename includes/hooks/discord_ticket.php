<?php
if (!defined("WHMCS"))
die("This file cannot be accessed directly");
use WHMCS\Database\Capsule;

add_hook('TicketOpen', 1, function($params) {
    // Discord Webhook URL
    $webhook_url = 'https://discord.com/api/webhooks/1109215709944348712/ZAGudriQhVMBKX6wHWgHN3mfzAhC2IunjuRpufvampQZVe8f5wH_N8njh-jqJ213wmap';

    // Get the ticket data
    $data = localAPI('GetTicket', array(
        'ticketid' => $params['ticketid'],
    ));

    // Create the message to send to Discord
    $message = "Yeni bir destek bileti açıldı.\n\n";
    $message .= "**Bilet ID:** {$params['ticketid']}\n";
    $message .= "**Departman:** {$data['deptname']}\n";
    $message .= "**Konu:** {$data['subject']}\n";
    $message .= "**Öncelik:** {$data['priority']}\n";
    $message .= "**Durum:** {$data['status']}\n";
    $message .= "**URL:** https://yologaming.org/whmcs/tostingnetpanelburasigardasla/supporttickets.php?action=view&id={$params['ticketid']}";

    // Send the message to Discord
    $data = array(
        'content' => $message,
    );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($webhook_url, false, $context);
});
?>