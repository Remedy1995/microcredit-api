<?phperror_reporting(0);?>
<?php
$msg = "";
//check if the user has clicked the button "UPLOAD"
 if (isset($_POST['uploadfile'])) {
     $filename = $_FILES["choosefile"]["name"];
  $tempname = $_FILES["choosefile"]["tmp_name"];
  $folder = "image/".$filename;
      // connect with the database
       $db = mysqli_connect("localhost", "root", "", "Image_upload");
             // query to insert the submitted data
              $sql = "INSERT INTO image (filename) VALUES ('$filename')";
                   // function to execute above query
                    mysqli_query($db, $sql);
                     // Add the image to the "image" folder"
                        if (move_uploaded_file($tempname, $folder)) {
                                       $msg = "Image uploaded successfully";
                                        }else{            $msg = "Failed to upload image";    }}
                                        $result = mysqli_query($db, "SELECT * FROM image");?>
                                         <!DOCTYPE html><html> <!DOCTYPE html><html>
                                             <head>    <title>Image Upload in PHP</title>
                                               <! link the css file to style the form >
                                                 <link rel="stylesheet" type="text/css" href="style.css" />  <style type="text/css">        #wrapper{            width: 50%;            margin: 20px auto;            border: 2px solid #dad7d7;        }        form{            width: 50%;            margin: 20px auto;        }        form div{            margin-top: 5px;        }        img{            float: left;            margin: 5px;            width: 280px;            height: 120px;        }        #img_div{            width: 70%;            padding: 5px;            margin: 15px auto;            border: 1px solid #dad7d7;        }        #img_div:after{            content: "";            display: block;            clear: both;        }    </style></head> <body>    <div id="wrapper">         <! specify the encoding type of the form using the                 enctype attribute >
                                                  <form method="POST" action="" enctype="multipart/form-data">
                                                                    <input type="file" name="choosefile" value="" />
                                                                               <div>
                                                                                 <button type="submit" name="uploadfile">WAMP or XAMPP server                UPLOAD                </button>            </div>        </form>    </div></body></html>
Output
Become an Automation Test Engineer in 11 Months!
Automation Testing Masters ProgramExplore ProgramBecome an Automation Test Engineer in 11 Months!
New Database Using phpMyAdmin.
Database name: Image_Upload
Table name: Image
Image_Upload_PHP_1

HTML Form.
Image_Upload_PHP_2.

Steps to Exceed the Size of Image Upload
The program depicted above can upload a file of up to 2MB in size. This is the default file size in PHP. This size limit can be updated and exceeded according to your choice. To increase the size limit for file upload, follow the steps discussed below:

Go to the C drive and open the folder named WAMP or XAMPP server.
Click on “bin” to open this folder.
Open the folder named as the PHP version (the version which you are using).
In this folder, search and go to “php.ini”.
Now search for the variables:
 upload_max_size = 100M
post_max_filesize = 100M
Update the new values of these variables and save them.
Now go to this path: “C:\wamp64\bin\apache\apache2.4.27\bin”.
Search and go to “php.ini” and make the same changes.
Save the changes.
Finally, restart your WAMP or XAMPP server.
Run your code on the server.
Wrapping Up!
In this article, you learned how to upload an image in PHP. This article also explored the different ways to upload a file using PHP. You saw the methods used for image upload in PHP in-depth. You then looked at the steps to increase the limit of the file size to be uploaded in PHP. Additionally, you also explored in detail the required codes and combined them to get the desired result.

To get started with PHP, you can refer to this video. You can also learn how to build dynamic applications using PHP with the help of an all-inclusive PHP training course. This course will allow you to get a strong grip on PHP, MySQL, Laravel 4, and other trending topics.

Don’t just stop here. To learn Full-stack Development and to give yourself a chance to work for top tech giants, check out our course on Full Stack Developer - MERN Stack. In this course, you will learn the complete end-to-end technologies/skills that will help you to set your foot into professional web development. These include Java, DevOps, Agility, HTML, AWS, etc.

Check out the complete list of free online courses by Simplilearn.

If you have any questions for us, please mention them in the comments section and our experts will answer them for you.

Happy Learning!
