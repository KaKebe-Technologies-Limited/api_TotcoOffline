<?php

include '../dbConfig.php';
include '../sanitizer.php';
include '../pepperConfig.php'; //$pepper added..

if (isset($_REQUEST['user_name']) || isset($_REQUEST['user_id'])) {
    if (isset($_REQUEST['user_name']) && $_REQUEST['user_name'] !== "") {
        $userName = $_REQUEST['user_name'];
        //handle cleanning here
        clean_input($userName);

        $sql = "UPDATE tbl_user SET ";
        $comma = 0;
        $atleast1Param = 0;

        // firstname
        if (isset($_REQUEST['firstname']) && $_REQUEST['firstname'] !== "") {
            $firstname = $_REQUEST['firstname'];
            clean_input($firstname);
            if ($comma) {
                $sql .= ", firstname = '$firstname'";
            } else {
                $sql .= " firstname = '$firstname'";
            }
            $comma = 1;
            $atleast1Param = 1;
            $response['firstname'] = "Found NOT Empty";
        } else {
            $response['firstname'] = "Found Empty";
        }

        // lastname
        if (isset($_REQUEST['lastname']) && $_REQUEST['lastname'] !== "") {
            $lastname = $_REQUEST['lastname'];
            clean_input($lastname);
            if ($comma) {
                $sql .= ", lastname = '$lastname'";
            } else {
                $sql .= " lastname = '$lastname'";
            }
            $comma = 1;
            $atleast1Param = 1;
            $response['lastname'] = "Found NOT Empty";
        } else {
            $response['lastname'] = "Found Empty";
        }

        // password - (1)special case - first confirm if the old password is really the one user has entered
        if (isset($_REQUEST['old_password']) && $_REQUEST['old_password'] !== "") {
            $old_password = $_REQUEST['old_password'];
            $sql1 = "SELECT * FROM tbl_user WHERE username = '$userName'";
    
            // $result = $conn->query($sql);
            if ($result1 = $conn->query($sql1)) {
                if ($result1->num_rows > 0) {
                    // Associative array
                    $row1 = $result1->fetch_assoc();
    
                    $pwd_peppered = hash_hmac("sha256", $old_password, $pepper);
    
                    if (password_verify($pwd_peppered, $row1["password"])) {
                        $response['message'] = "Login successful";
                        // password - (2)special case - encryption
                        if (isset($_REQUEST['password']) && $_REQUEST['password'] !== "") {
                            $password = $_REQUEST['password'];
                            clean_input($password);
                            // check for passwordConfirm
                            if (isset($_REQUEST['passwordConfirm']) && $_REQUEST['passwordConfirm'] !== "") {
                                $passwordConfirm = $_REQUEST['passwordConfirm'];
                                clean_input($passwordConfirm);
                                // do new passwords match
                                if ($password == $passwordConfirm) {
                                    //hash password
                                    $pwd_peppered = hash_hmac("sha256", $password, $pepper); //$pepper
                                    $pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2I);
                                    if ($comma) {
                                        $sql .= ", password = '$pwd_hashed'";
                                    } else {
                                        $sql .= " password = '$pwd_hashed'";
                                        $comma = 1;
                                    }
                                    $atleast1Param = 1;
                                    $response['password'] = "Found NOT Empty";
                                    $response['password_confirm'] = "Found NOT Empty";
                                } else {
                                    $response['message'] = "Password Mismatch";
                                }
                            } else {
                                $response['password_confirm'] = "Found Empty";
                            }
                        } else {
                            $response['password'] = "Found Empty";
                        }
                        // 
                    } else {
                        $response['success'] = 0;
                        $response['message'] = "Incorrect username or password";
                    }
                } else {
                    $response['success'] = 0;
                    $response['message'] = "Incorrect username or password";
                }
            } else {
                $response['success'] = 0;
                $response['message'] = "Server didn't respond, please try again!";
            }
        } else {
            $response['old_password'] = "Found Empty";
        }
        // email
        if (isset($_REQUEST['email']) && $_REQUEST['email'] !== "") {
            $email = $_REQUEST['email'];
            clean_input($email);
            if ($comma) {
                $sql .= ", email = '$email'";
            } else {
                $sql .= " email = '$email'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['email'] = "Found NOT Empty";
        } else {
            $response['email'] = "Found Empty";
        }
        // sex
        if (isset($_REQUEST['sex']) && $_REQUEST['sex'] !== "") {
            $sex = $_REQUEST['sex'];
            clean_input($sex);
            if ($comma) {
                $sql .= ", sex = '$sex'";
            } else {
                $sql .= " sex = '$sex'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['sex'] = "Found NOT Empty";
        } else {
            $response['sex'] = "Found Empty";
        }
        // date_of_birth
        if (isset($_REQUEST['date_of_birth']) && $_REQUEST['date_of_birth'] !== "") {
            $date_of_birth = $_REQUEST['date_of_birth'];
            clean_input($date_of_birth);
            if ($comma) {
                $sql .= ", date_of_birth = '$date_of_birth'";
            } else {
                $sql .= " date_of_birth = '$date_of_birth'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['date_of_birth'] = "Found NOT Empty";
        } else {
            $response['date_of_birth'] = "Found Empty";
        }
        // phonenumber
        if (isset($_REQUEST['phone_number']) && $_REQUEST['phone_number'] !== "") {
            $phone_number = $_REQUEST['phone_number'];
            clean_input($phone_number);
            if ($comma) {
                $sql .= ", phone_number = '$phone_number'";
            } else {
                $sql .= " phone_number = '$phone_number'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['phone_number'] = "Found NOT Empty";
        } else {
            $response['phone_number'] = "Found Empty";
        }
        // profile photo
        if (isset($_REQUEST['fileToUpload']) && $_REQUEST['fileToUpload'] !== "") {
            $profile_photo = $_REQUEST['fileToUpload'];
            clean_input($profile_photo);
            if ($comma) {
                $sql .= ", profile_photo = '$profile_photo'";
            } else {
                $sql .= " profile_photo = '$profile_photo'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['profile_photo'] = "Found NOT Empty";
        } else {
            $response['profile_photo'] = "Found Empty";
        }
        // isAdmin
        if (isset($_REQUEST['isAdmin']) && $_REQUEST['isAdmin'] !== "") {
            $isAdmin = $_REQUEST['isAdmin'];
            clean_input($isAdmin);
            if ($comma) {
                $sql .= ", isAdmin = '$isAdmin'";
            } else {
                $sql .= " isAdmin = '$isAdmin'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['isAdmin'] = "Found NOT Empty";
        } else {
            $response['isAdmin'] = "Found Empty";
        }
        // approve isAgent
        if (isset($_REQUEST['isAgent']) && $_REQUEST['isAgent'] !== "") {
            $isAgent = $_REQUEST['isAgent'];
            clean_input($isAgent);
            if ($comma) {
                $sql .= ", isAgent = '$isAgent'";
            } else {
                $sql .= " isAgent = '$isAgent'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['isAgent'] = "Found NOT Empty";
        } else {
            $response['isAgent'] = "Found Empty";
        }
        // approve isSystemAdmin
        if (isset($_REQUEST['isSystemAdmin']) && $_REQUEST['isSystemAdmin'] !== "") {
            $isSystemAdmin = $_REQUEST['isSystemAdmin'];
            clean_input($isSystemAdmin);
            if ($comma) {
                $sql .= ", isSystemAdmin = '$isSystemAdmin'";
            } else {
                $sql .= " isSystemAdmin = '$isSystemAdmin'";
                $comma = 1;
            }
            $atleast1Param = 1;
            $response['isSystemAdmin'] = "Found NOT Empty";
        } else {
            $response['isSystemAdmin'] = "Found Empty";
        }
        
        // Now, firing of the sql statement in complete format required
        if ($atleast1Param) {
            $sql .= " WHERE username = '$userName'";

            if ($conn->query($sql) === TRUE) {
                $response['success'] = 1;
                $response['message'] = "User updated successfully";
            } else {
                $response['success'] = 0;
                $response['message'] = "Error updating user: " . $conn->error;
            }
        } else {
            $response['success'] = 0;
            $response['message'] = "Please select a field to update!";
        }
    } else {
        $response['success'] = 0;
        $response['message'] = "Confirm user to update!";
    }
} else {
    $response['success'] = 0;
    $response['message'] = "Please select user to update!";
}

echo json_encode($response);

$conn->close();
