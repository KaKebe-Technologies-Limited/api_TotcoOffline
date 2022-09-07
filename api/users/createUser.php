<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..

if (isset($_REQUEST['password'])  && isset($_REQUEST['passwordConfirm'])  && $_REQUEST['password'] !== "" && $_REQUEST['passwordConfirm'] !== "") {
    if (isset($_REQUEST['user_name'])  && $_REQUEST['user_name'] !== "") {
        $userName = $_REQUEST['user_name'];

        $firstName = isset($_REQUEST['first_name'])  && $_REQUEST['first_name'] !== "" ? $_REQUEST['first_name'] : null;
        $lastName = isset($_REQUEST['last_name'])  && $_REQUEST['last_name'] !== "" ? $_REQUEST['last_name'] : null;
        $email = isset($_REQUEST['email'])  && $_REQUEST['email'] !== "" ? $_REQUEST['email'] : null;

        $phoneNumber = isset($_REQUEST['phone_number'])  && $_REQUEST['phone_number'] !== "" ? $_REQUEST['phone_number'] : null;
        $date_of_birth = isset($_REQUEST['date_of_birth'])  && $_REQUEST['date_of_birth'] !== "" ? $_REQUEST['date_of_birth'] : null;
        $sex = isset($_REQUEST['sex'])  && $_REQUEST['sex'] !== ""  ? $_REQUEST['sex'] : null   ;

        $password = $_REQUEST['password'];
        $passwordConfirm = $_REQUEST['passwordConfirm'];
    
        //handle cleanning here
        clean_input($firstName);
        clean_input($lastName);
        clean_input($userName);
        clean_input($email);
        clean_input($sex);
        clean_input($phoneNumber);
        clean_input($date_of_birth);
        clean_input($password);
        clean_input($passwordConfirm);
    
        //check if userName already exists
        $sql = "SELECT username FROM  tbl_user WHERE username = '$userName' LIMIT 1";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            //at this stage, we got a result from db, meaning: username already exists!
            $response['success'] = 0;
            $response['message'] = "Username already taken!";
        } else {
            //username doesn't exist, so add the user into the db
    
            if ($password == $passwordConfirm) {
                //hash password
                $pwd_peppered = hash_hmac("sha256", $password, $pepper); //$pepper
                $pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2I); //PASSWORD_ARGON2I
    
                // prepare and bind
                $stmt = $conn->prepare("INSERT INTO tbl_user (firstname, lastname, username, email, password, phone_number, date_of_birth, sex) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $param_FistName, $param_LastName, $param_UserName, $param_Email, $param_Password, $param_PhoneNumber, $param_DateOfBirth, $param_Sex );
    
                // set parameters and execute
                $param_FistName = $firstName;
                $param_LastName = $lastName;
                $param_UserName = $userName;
                $param_Email = $email;
                $param_Password = $pwd_hashed;
                $param_Sex = $sex;
                $param_PhoneNumber = $phoneNumber;
                $param_DateOfBirth = $date_of_birth;
                // $stmt->execute();
    
                if ($stmt->execute()) {
                    $response['success'] = 1;
                    $response['message'] = "New user (record) created successfully";
                } else {
                    $response['success'] = 0;
                    $response['message'] = "NOT INSERTED!";
                }
                $stmt->close();
            } else {
                $response['success'] = 0;
                $response['message'] = "Password Mismatch";
            }
        }
    } else {
        $response['success'] = 0;
        $response['message'] = "Please provide Username";
    }

} else {
    $response['success'] = 0;
    $response['message'] = "Please fill in all Fields!";
}

echo json_encode($response);


$conn->close();

