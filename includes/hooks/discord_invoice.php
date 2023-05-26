<?php
if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

add_hook('InvoicePaid', 1, function($vars) {
    // Discord Webhook URL
    $webhook_url = 'https://discord.com/api/webhooks/1111490367586578532/rl2utwVvyqqbJSfxa1IeGDD-g2oUX8WD6BNlVZn5ikqODmBXXrUQjfkZa9L3Q7kdAka5';

    // Get the invoice data
    $invoiceid = $vars['invoiceid'];
    $invoice = localAPI('GetInvoice', array(
        'invoiceid' => $invoiceid,
    ));
    $userid = $invoice['userid'];
    $client = localAPI('GetClientsDetails', array(
        'clientid' => $userid,
    ));

    // Create the message to send to Discord
    $message = "Yeni bir fatura ödemesi yapıldı.\n\n";
    $message .= "**Fatura ID:** #{$invoiceid}\n";
    $message .= "**Müşteri Adı:** {$client['fullname']}\n";
    $message .= "**Müşteri E-Posta:** {$client['email']}\n";

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

    // Make the API request
    $context  = stream_context_create($options);
    $result = file_get_contents($webhook_url, false, $context);

    return $result;
});
?>
