
let messages = document.querySelector(".message_box").querySelectorAll("textarea");

// messages.forEach(function(item) {
//   item.style.height = (item.scrollHeight) + "px";
// });



document.querySelector(".message_box").scrollTop = document.querySelector(".message_box").scrollHeight 

/*
$db2 = mysqli_connect("localhost", "root", "", "messages");
//fetches messages which you sent to this exact friend
$my_message_query =  "SELECT * FROM `$localid` WHERE reciever=$Adress_id";
$my_message_results = mysqli_query($db2, $my_message_query);
//fetches messages which this friend sent you
$guests_message_query = "SELECT * FROM `$Adress_id` WHERE reciever=$localid";
$guests_message_results = mysqli_query($db2, $guests_message_query);
//array to store messages
$message_array = array();

//Yours messages
if (mysqli_num_rows($my_message_results)) {
  while ($row = mysqli_fetch_assoc($my_message_results)) {
    //Pushes time and message into array
    $message_array[$row['time'] .= 'local'] = $row['message'];
  }
}
//Friends messages
if (mysqli_num_rows($guests_message_results)) {
  while ($row = mysqli_fetch_assoc($guests_message_results)) {
    //Pushes time and message into array
    $message_array[$row['time'] .= 'guest'] = $row['message'];
  }
}

//Sorts messages by time
ksort($message_array);
*/