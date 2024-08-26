<head>
    <style>
        h1{
            text-align: center;
        }
        label{
            font-size: x-large;
        }
        input{
            width: 60vw;
        }
        textarea
        {
            height: 10vw;
            width: 60vw;
            resize: none;
        }
        .design {
            background-color: #fff;
            padding: 20px;
            border-radius: 30px;
            width: 60vw;
            margin-left: 15vw;
            margin-top: 8vw;
        }
        #btn{
            background-color: transparent;
            color: black;
            height: 2vw;
            width: 12vw;
            margin-left: 25vw;
            border-radius: 20px;
            font-weight: bold;
        }
        #btn:hover{
            background-color: #54f554;
            color: #ff0e0e;
        }
    </style>
</head>

<div class="design">
    <h1><?php echo esc_html__('Email Sender', 'sendgridPlugin'); ?></h1>
    <form id="sendgridEmailForm" method="post" action="">
        <label for="toEmail"><?php echo esc_html__('To', 'sendgridPlugin'); ?></label><br><br>
        <input type="email" id="toEmail" name="to_email" required><br><br>
        <br>
        <label for="subject"><?php echo esc_html__('Subject', 'sendgridPlugin'); ?></label><br><br>
        <input type="text" id="subject" name="subject" required><br><br>
        <br>
        <label for="content"><?php echo esc_html__('Message', 'sendgridPlugin'); ?></label><br><br>
        <textarea id="content" name="content" required></textarea><br><br>
        <br>
        <input type="submit" value="<?php echo esc_html__('Send Mail', 'sendgridPlugin'); ?>" id="btn">
    </form>
</div>



