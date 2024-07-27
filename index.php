<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$encryptionKey = 'your-encryption-key'; // Use a secure key
$cipher = "aes-256-cbc"; // Cipher method
$ivLength = openssl_cipher_iv_length($cipher);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'send_message') {
    $message = $_POST['message'];
    $filePath = '';

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        // Sanitize file name to prevent issues with special characters
        $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);

        // Define the path to save the file
        $filePath = 'uploads/' . uniqid() . '_' . $fileName;

        // Check if the file was successfully moved to the uploads directory
        if (!move_uploaded_file($fileTmpName, $filePath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
            exit();
        }
    }

    // Encrypt the message
    $iv = openssl_random_pseudo_bytes($ivLength);
    $encryptedMessage = openssl_encrypt($message, $cipher, $encryptionKey, 0, $iv);
    $encryptedMessage = base64_encode($encryptedMessage . '::' . base64_encode($iv));

    // Prepare and execute database query
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message, file_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $encryptedMessage, $filePath);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
    exit();
}

// Handle message fetching
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'fetch_messages') {
    $messages = $conn->query("
        SELECT users.username, users.online_status, users.last_activity, messages.user_id, messages.message, messages.created_at, messages.file_path
        FROM messages
        JOIN users ON messages.user_id = users.id
        ORDER BY messages.created_at ASC
    ");

    $result = [];
    while ($row = $messages->fetch_assoc()) {
        // Decrypt the message
        $decryptedMessage = decryptMessage($row['message'], $encryptionKey);

        // Determine user status display
        $userStatus = $row['online_status'] === 'online' 
            ? 'Online' 
            : 'Last active: ' . date('Y-m-d H:i:s', strtotime($row['last_activity'])); // Adjust format as needed

        $result[] = [
            'username' => htmlspecialchars($row['username']),
            'user_id' => $row['user_id'],
            'message' => formatMessage($decryptedMessage),
            'created_at' => htmlspecialchars($row['created_at']),
            'file_path' => htmlspecialchars($row['file_path']),
            'user_status' => $userStatus // Add user status to the result
        ];
    }
    echo json_encode($result);
    exit();
}

// Format messages to handle links and phone numbers
function formatMessage($message) {
    $message = htmlspecialchars($message);
    $message = preg_replace('/(\+880\d{10})/', '<a href="tel:$1">$1</a>', $message);
    $message = preg_replace('/\b(http:\/\/\S+|https:\/\/\S+|www\.\S+)\b/i', '<a href="$1" target="_blank">$1</a>', $message);
    return nl2br($message);
}

// Decrypt messages
function decryptMessage($encryptedMessage, $key) {
    global $cipher;
    $ivLength = openssl_cipher_iv_length($cipher);
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedMessage), 2);
    $iv = base64_decode($iv);
    $decryptedMessage = openssl_decrypt($encryptedData, $cipher, $key, 0, $iv);
    return $decryptedMessage !== false ? $decryptedMessage : 'Decryption error.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
       .chat-box {
            border: 1px solid #ddd;
            padding: 10px;
            overflow-y: scroll;
            background-color: #f9f9f9;
          
            
            height: calc(65vh); /* Adjust height based on form and header size */
        }
        .message {
            margin-bottom: 15px;
        }
        .message img, .message video {
            max-width: 100%;
            height: auto;
            margin-top: 5px;
        }
        .message strong {
            color: #007bff;
        }
        .message time {
            font-size: 0.8rem;
            color: #6c757d;
            display: block;
        }
        .message-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh); /* Adjust height based on form and header size */
        }
        .message-left {
            text-align: left;
        }
        .message-right {
            text-align: right;
        }
        .message-left img, .message-right img,
        .message-left video, .message-right video {
            margin: 0;
        }
        .message-content {
            display: inline-block;
            max-width: 75%;
            padding: 10px;
            border-radius: 10px;
        }
        .message-left .message-content {
            background-color: #e9ecef;
            border: 1px solid #ddd;
            margin-right: auto;
        }
        .message-right .message-content {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
            margin-left: auto;
        }
        .message-right a {
            color: white;
            text-decoration: underline;
        }
        /* Style for online status */
.status-online {
    color: green; /* Green text for online users */
}

/* Style for offline status */
.status-offline {
    color: black; /* Black text for offline users */
}


        .form-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-container .file-upload {
            flex: 0;
        }
        .form-container .message-input {
            flex: 3;
        }
        .form-container .send-button {
            display: none;
            margin-left: 0px;
        }
        .form-container .send-button.visible {
            display: inline-block;
        }
        .form-container .file-button {
            display: block;
            margin-left: 0;
        }
        .form-container .file-button.hidden {
            display: none;
        }
    </style>
</head>
<body class="container my-4">
    <div style="position: relative;">
        <h2 class="text-center mb-3">Group Chat</h2>
        
       
        <div style="position: absolute; top: 0; left: 0;">
        <button onClick="window.location.href=window.location.href" class="btn btn-primary mt-3">Refresh</button>
    </div>

    <div style="position: absolute; top: 0; right: 0;">
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>

    <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); margin-top: 90px;">
        <p class="" id="typingStatus"></p>
    </div>

    <div id="onlineUsers" class="mb-2">Online: No User</div>
    <center></center>
   
            <div id="chatBox" class="chat-box pt-3"></div>










       

            <form method="post" enctype="multipart/form-data" id="chatForm" class="form-container mt-3">
        <input type="hidden" name="action" value="send_message">
        
        <div class="file-upload file-button" id="fileBtn">
           
            <input type="file" id="selectedFile" style="display: none;" name="file" class="form-control-file">
<input type="button" value="🖇️" onclick="attachBtn()" />
        </div>

        <div class="message-input">
            <textarea name="message" id="messageInput" class="form-control" rows="2" placeholder="Type your message..." ></textarea>
        </div>

        <input type="submit" id="sendButton" value="Send" onclick="rmvSend()" class="btn btn-primary send-button">
    </form>













            
        </div>
    </div>

    <script src="jquery-3.5.1.min.js"></script>

    <script>
        function rmvSend (){
            var sendButton = document.getElementById('sendButton');
            var fileButton = document.getElementById('fileBtn');
    sendButton.classList.remove('visible');
    fileButton.classList.remove('hidden');
}

function attachBtn (){
    var sendButton = document.getElementById('sendButton');
    var fileButton = document.getElementById('fileBtn');
    document.getElementById('selectedFile').click();
    sendButton.classList.add('visible');
}

document.getElementById('selectedFile').addEventListener('change', function() {
            // Submit the form automatically when a file is selected

            console.log('dfds')
            document.querySelector('#chatForm input[type="submit"]').click(); 
        });
        
    document.getElementById('messageInput').addEventListener('input', function() {
        var sendButton = document.getElementById('sendButton');
        var fileButton = document.getElementById('fileBtn');
        var typingStatus = this.value.trim() !== '';
        
        if (typingStatus) {
            sendButton.classList.add('visible');
            fileButton.classList.add('hidden');
        } else {
            sendButton.classList.remove('visible');
            fileButton.classList.remove('hidden');
        }
        
        // Update typing status in the database
        updateTypingStatus(typingStatus);
    });

    function updateTypingStatus(isTyping) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_typing_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("typing_status=" + (isTyping ? 'true' : 'false'));
    }


        // Initially, file button should be visible
        document.getElementById('messageInput').addEventListener('focus', function() {
            document.getElementById('fileBtn').classList.add('hidden');
        });

        document.getElementById('messageInput').addEventListener('blur', function() {
            if (this.value.trim() === '') {
                document.getElementById('fileBtn').classList.remove('hidden');
            }
        });
        
    </script>
    <script>
        $(document).ready(function() {
            let isScrolling = false;
            let scrollTimeout;

            $('#chatForm').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            $('#messageInput').val('');
                        $('input[type=file]').val('');
                        loadMessages();
                        } else {
                            alert(result.message || 'Error sending message.');
                        }
                    }
                });
            });
            // Load messages
            function loadMessages() {
                $.ajax({
                    url: 'index.php',
                    type: 'GET',
                    data: { action: 'fetch_messages' },
                    success: function(response) {
                        var messages = JSON.parse(response);
                        $('#chatBox').html('');
                        messages.forEach(function(message) {
                            var isUserMessage = message.user_id == <?= json_encode($user_id) ?>;
                            var messageClass = isUserMessage ? 'message-right' : 'message-left';
                            
                            // Determine the status class
                            var statusClass = message.user_status === 'Online' ? 'status-online' : 'status-offline';
                            
                            var messageContent = `
                                <div class="message ${messageClass}">
                                    <strong class="${statusClass}">${message.username} <span class="${statusClass}">[${message.user_status}]</span></strong>
                                    <time>${message.created_at}</time>
                                    <div class="message-content">
                                        ${message.message}
                                        ${message.file_path ? generateFileHTML(message.file_path) : ''}
                                    </div>
                                </div>
                            `;
                            $('#chatBox').append(messageContent);
            });
            $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight); // Auto-scroll to the bottom
        }
    });

}


            function generateFileHTML(filePath) {
                var ext = filePath.split('.').pop().toLowerCase();
                var fileHTML = '';
                if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                    fileHTML = `<a href="${filePath}" target="_blank">
                                    <img src="${filePath}" alt="Image">
                                 </a>`;
                
                } else {
                    fileHTML = `<a href="${filePath}" target="_blank" class="btn btn-link">Download ${ext.toUpperCase()} File</a>`;
                }
                return fileHTML;
            }

    
// Handle scrolling
$('#chatBox').on('scroll', function() {
    isScrolling = true; // User is scrolling

    clearTimeout(scrollTimeout); // Clear previous timeout if it exists
    scrollTimeout = setTimeout(function() {
        isScrolling = false; // User stopped scrolling
    }, 500); // Delay after scrolling stops
});

// Fetch messages every 1 second, but only if not scrolling or scrolled to the bottom
setInterval(function() {
    if (!isScrolling && isAtBottom()) {
        loadMessages();
    }
}, 500);

// Function to check if the user is at the bottom of the chat box
function isAtBottom() {
    var chatBox = $('#chatBox');
    return chatBox[0].scrollHeight - chatBox.scrollTop() <= chatBox.outerHeight();
}
        });

        document.getElementById('messageInput').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault(); // Prevent the default newline behavior
        document.querySelector('#chatForm input[type="submit"]').click(); // Simulate the form submission
    }
});


        $(document).ready(function() {
            // Function to update the user's online status
            function updateOnlineStatus(status) {
                $.post('update_status.php', { status: status });
            }

            // Function to fetch and update online users
            function updateOnlineUsers() {
                $.getJSON('fetch_status_updates.php', function(data) {
                    // Extract usernames and join them with commas
                    let onlineUsernames = data.map(user => user.username).join(', ');

                    // Update the HTML element with the concatenated usernames
                    $('#onlineUsers').text('Online: ' + onlineUsernames);
                });
            }

            // Page visibility change event listener
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    updateOnlineStatus('online');
                } else {
                    updateOnlineStatus('offline');
                }
            });

            // Initial call to set status as online when the page loads
            updateOnlineStatus('online');

            // Periodically fetch and update online users
            setInterval(updateOnlineUsers, 5000); // 5 seconds in milliseconds

            // Ensure we send offline status when the user leaves the page
            $(window).on('beforeunload', function() {
                updateOnlineStatus('offline');
            });
        });
   


        $(document).ready(function() {
            function fetchTypingStatus() {
                $.getJSON('fetch_typing_status.php', function(data) {
                    if (data.length > 0) {
                        let typingUsers = data.join(', ');
                        $('#typingStatus').text(typingUsers + ' is typing...');
                    } else {
                        $('#typingStatus').text('');
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Failed to fetch typing status:', textStatus, errorThrown);
                });
            }

            // Fetch typing status periodically
            setInterval(fetchTypingStatus, 2000); // Every 2 seconds

            // Handle user input for typing status
            let typingTimeout;
            $('#messageInput').on('input', function() {
                clearTimeout(typingTimeout);
                $.post('update_typing_status.php', { typing_status: 1 });
                
                typingTimeout = setTimeout(function() {
                    $.post('update_typing_status.php', { typing_status: 0 });
                }, 3000); // Reset typing status after 3 seconds
            });

            // Initialize typing status
            $.post('update_typing_status.php', { typing_status: 0 });
        });
    </script>



<script>
        document.addEventListener('DOMContentLoaded', () => {
            const originalTitle = document.title;
            const hiddenTitle = 'Come Back!';

            function handleVisibilityChange() {
                if (document.hidden) {
                    document.title = hiddenTitle;
                } else {
                    document.title = originalTitle;
                }
            }

            document.addEventListener('visibilitychange', handleVisibilityChange);
        });
    </script>

</body>
</html>
