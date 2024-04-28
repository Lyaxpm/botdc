<?php

include __DIR__.'/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

$discord = new Discord([
  'token' => 'MTIzNDIxODQzMjgzNzQ1MTgwNw.GtNUbk.WB_5Z-ZLPu3K2zTJi12PFm9cyakRXfCNt7QJM4' // Replace 'YOUR_BOT_TOKEN' with your actual bot token
]);

$discord->on('ready', function (Discord $discord) {
  echo "Bot is ready!", PHP_EOL;

  // Listen for messages in the server
  $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
    // Check if the message is from a bot
    if ($message->author->bot) {
      return;
    }

    // Respond to a specific command
    if (strtolower(substr($message->content, 0, 4)) === '!ai ') {

      $ch = curl_init();
      // Set URL dan query parameters
      $baseUrl = "https://api.velixs.com/nakiri";
      $text = trim(substr($message->content, 4));
      $apiKey = "c103d389792b8a4e83aa2c7fd7d48e4717608503f93e3686a6"; // Ganti dengan API key yang sebenarnya
      $url = sprintf("%s?text=%s&apikey=%s", $baseUrl, urlencode($text), $apiKey);

      // Set opsi cURL
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      // Eksekusi cURL
      $response = curl_exec($ch);
      $resp = json_decode($response, true);
      // Tutup koneksi cURL
      curl_close($ch);

      // Array kata yang ingin diganti
      $search = array("Nakiri", "Velixs");

      // Array kata pengganti
      $replace = array("Abi", "Gen Asagiri");


      $newText = str_replace($search, $replace, $resp['data']['reply']);

      $message->channel->sendMessage($newText);
    }
  });
});

$discord->run();
