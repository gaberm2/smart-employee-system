<?php 
include('../inc/topbar.php'); 

// Initialize session variables
$_SESSION['success'] = '';
$_SESSION['error'] = '';

if (isset($_POST["btnsubmit"])) {
    // Generate employee ID
    $employeeID = 'STAFF/FKP/' . date("Y") . '/' . rand(1000, 5009);  

    // Prepare SQL statement - storing plain text password (NOT RECOMMENDED)
    $sql = 'INSERT INTO tblemployee(
        employeeID, fullname, password, sex, email, dob, phone, address, 
        qualification, dept, employee_type, date_appointment, basic_salary, 
        gross_pay, status, leave_status, photo
    ) 
    VALUES (
        :employeeID, :fullname, :password, :sex, :email, :dob, :phone, :address, 
        :qualification, :dept, :employee_type, :date_appointment, :basic_salary, 
        :gross_pay, :status, :leave_status, :photo
    )';

    try {
        $statement = $dbh->prepare($sql);
        $result = $statement->execute([
            ':employeeID'       => $employeeID,
            ':fullname'         => htmlspecialchars($_POST['txtfullname']),
            ':password'         => $_POST['txtpassword'], // Storing plain text password
            ':sex'             => htmlspecialchars($_POST['cmdsex']),
            ':email'           => htmlspecialchars($_POST['txtemail']),
            ':dob'             => htmlspecialchars($_POST['txtdob']), 
            ':phone'           => htmlspecialchars($_POST['txtphone']),
            ':address'         => htmlspecialchars($_POST['txtaddress']),
            ':qualification'   => htmlspecialchars($_POST['txtqualification']),
            ':dept'            => htmlspecialchars($_POST['cmddept']),
            ':employee_type'   => htmlspecialchars($_POST['cmdemployeetype']),
            ':date_appointment' => htmlspecialchars($_POST['txtdate_appointment']),
            ':basic_salary'    => floatval($_POST['txtbasic_salary']),
            ':gross_pay'       => floatval($_POST['txtgross_pay']),
            ':status'          => '1',
            ':leave_status'    => 'Not Available',
            ':photo'           => 'uploadImage/Profile/default.png'
        ]);

        if ($result) {
            $_SESSION['success'] = 'Registration was Successful. Your password is: ' . $_POST['txtpassword'];
            // Redirect to prevent form resubmission
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = 'Something went Wrong with the database operation';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration - <?php echo $sitename; ?></title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="../css/iofrm-theme13.css">
    <link href="../css/auth.css" rel="stylesheet" />
    <link href="../plugin/TelPlugin/build/css/intlTelInput.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../app-assets/vendors/css/switchery.min.css">
    <link href="../css/doublesided.css" rel="stylesheet" />
    <style>
        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body style="height:100vh;overflow-y:auto;" class="d-flex flex-column">

    <div class="form-body">
        <div class="row">
            <div class="img-holder d-flex flex-column">
                <div class="aa"></div>
                <div class="bb"></div>
                <div class="info-holder" style="z-index:4;">
                    <br />
                    <a class="btn btn-outline-light text-white" style="z-index: 10000;position: relative;" href="../index.php">← Home</a>
                </div>
                <section class="footer pt-0 mt-auto bg-dark" style="z-index:1000">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="text-center footer-alt my-1">
                                    <p class="f-15">
                                        <?php include('../inc/footer2.php'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="form-holder" style="height: 100%;overflow-y: auto; max-height:unset;">
                <div class="aa"></div>
                <div class="bb"></div>

                <div class="form-content" style="max-height: unset;">
                    <div class="form-items card" style="z-index:100">
                        <div class="card-header d-flex flex-wrap">
                            <a href="../index.php" style="z-index: 3;" class="btn p-2 btn-outline-primary mr-auto d-inline-flex align-items-center">
                                <i class="fas fa-home fa-2x" title="home"></i>
                            </a>
                            <h4 class="mr-auto my-auto text-center">Employee Registration</h4>
                            <div class="ml-auto"></div>
                        </div>

                        <div class="card-body">
                            <form method="post" id="regForm" class="form form-horizontal" novalidate autocomplete="off" action="">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtfullname" class="required-field">Fullname</label>
                                            <div class="controls">
                                                <input class="form-control" type="text" placeholder="Fullname" required 
                                                       data-validation-required-message="Fullname is required" 
                                                       id="txtfullname" name="txtfullname" value="<?php echo isset($_POST['txtfullname']) ? htmlspecialchars($_POST['txtfullname']) : ''; ?>">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtphone" class="required-field">Mobile Number</label>
                                            <div class="controls">
                                                <input class="form-control" type="text" placeholder="Mobile Number" required 
                                                       data-validation-required-message="Mobile Number is required" 
                                                       id="txtphone" name="txtphone" value="<?php echo isset($_POST['txtphone']) ? htmlspecialchars($_POST['txtphone']) : ''; ?>">
                                                <span id="error-msg" class="hide"></span>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="cmdsex" class="required-field">Gender</label>
                                            <div class="controls">
                                                <select class="form-control" required 
                                                        data-validation-required-message="Select Sex" 
                                                        id="cmdsex" name="cmdsex">
                                                    <option value="">Select Sex</option>
                                                    <option value="Male" <?php echo (isset($_POST['cmdsex']) && $_POST['cmdsex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo (isset($_POST['cmdsex']) && $_POST['cmdsex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                </select>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtemail" class="required-field">Email Address</label>
                                            <div class="controls">
                                                <input type="email" autocomplete="off" class="form-control" required 
                                                       data-validation-required-message="Email Address is required" 
                                                       data-validation-regex-regex="([a-zA-Z0-9_\.-]+)@([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6})" 
                                                       data-validation-regex-message="Email Address not valid" 
                                                       placeholder="Email Address" 
                                                       id="txtemail" name="txtemail" value="<?php echo isset($_POST['txtemail']) ? htmlspecialchars($_POST['txtemail']) : ''; ?>">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtpassword" class="required-field">Password</label>
                                            <div class="controls">
                                                <input class="form-control" type="password" placeholder="Password" autocomplete="new-password" required 
                                                       data-validation-regex-regex="^(?=.*[A-z])(?=.*[A-Z])(?=.*[0-9])\S{6,12}$" 
                                                       data-validation-regex-message="Password must contain at least an Uppercase, Lowercase and a Number and must be between 6 and 12 digits" 
                                                       id="txtpassword" maxlength="12" name="txtpassword">
                                                <small class="form-text text-muted">6-12 characters with at least one uppercase, one lowercase, and one number</small>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="cmddept" class="required-field">Department</label>
                                            <div class="controls">
                                                <select class="form-control" required 
                                                        data-validation-required-message="Select Employee Department" 
                                                        id="cmddept" name="cmddept">
                                                    <option value="">... Select Department ...</option>
                                                    <option value="Human resources" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Human resources') ? 'selected' : ''; ?>>Human resources</option>
                                                    <option value="Finance" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Finance') ? 'selected' : ''; ?>>Finance</option>
                                                    <option value="Sales" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                                                    <option value="Technical support" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Technical support') ? 'selected' : ''; ?>>Technical support</option>
                                                    <option value="Admin" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                                    <option value="Graphic Design" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Graphic Design') ? 'selected' : ''; ?>>Graphic Design</option>
                                                    <option value="Programming" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Programming') ? 'selected' : ''; ?>>Programming</option>
                                                    <option value="Information Technology" <?php echo (isset($_POST['cmddept']) && $_POST['cmddept'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                                </select>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="cmdemployeetype" class="required-field">Employee Type</label>
                                            <div class="controls">
                                                <select class="form-control" required 
                                                        data-validation-required-message="Select Employee Type" 
                                                        id="cmdemployeetype" name="cmdemployeetype">
                                                    <option value="">Select Employee Type</option>
                                                    <option value="Employee" <?php echo (isset($_POST['cmdemployeetype']) && $_POST['cmdemployeetype'] == 'Employee') ? 'selected' : ''; ?>>Employee</option>
                                                    <option value="Department manager" <?php echo (isset($_POST['cmdemployeetype']) && $_POST['cmdemployeetype'] == 'Department manager') ? 'selected' : ''; ?>>Department manager</option>
                                                </select>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtbasic_salary" class="required-field">Basic Salary</label>
                                            <div class="controls">
                                                <input class="form-control" type="number" step="0.01" placeholder="Basic Salary" required 
                                                       data-validation-required-message="Basic Salary is required" 
                                                       id="txtbasic_salary" name="txtbasic_salary" 
                                                       value="<?php echo isset($_POST['txtbasic_salary']) ? htmlspecialchars($_POST['txtbasic_salary']) : ''; ?>">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtgross_pay" class="required-field">Gross Pay</label>
                                            <div class="controls">
                                                <input class="form-control" type="number" step="0.01" placeholder="Gross Pay" required 
                                                       data-validation-required-message="Gross Pay is required" 
                                                       id="txtgross_pay" name="txtgross_pay" 
                                                       value="<?php echo isset($_POST['txtgross_pay']) ? htmlspecialchars($_POST['txtgross_pay']) : ''; ?>">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtdate_appointment" class="required-field">Date of Appointment</label>
                                            <div class="controls">
                                                <input class="form-control" type="date" placeholder="Date of Appointment" required 
                                                       data-validation-required-message="Date of Appointment is required" 
                                                       id="txtdate_appointment" name="txtdate_appointment" 
                                                       value="<?php echo isset($_POST['txtdate_appointment']) ? htmlspecialchars($_POST['txtdate_appointment']) : ''; ?>">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtdob">Date of Birth</label>
                                            <div class="controls">
                                                <input class="form-control" type="date" placeholder="Date of Birth" 
                                                       id="txtdob" name="txtdob" 
                                                       value="<?php echo isset($_POST['txtdob']) ? htmlspecialchars($_POST['txtdob']) : ''; ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtaddress">Address</label>
                                            <div class="controls">
                                                <textarea class="form-control" placeholder="Address" 
                                                          id="txtaddress" name="txtaddress"><?php echo isset($_POST['txtaddress']) ? htmlspecialchars($_POST['txtaddress']) : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 form-group mb-2">
                                            <label for="txtqualification">Qualification</label>
                                            <div class="controls">
                                                <input class="form-control" type="text" placeholder="Qualification" 
                                                       id="txtqualification" name="txtqualification" 
                                                       value="<?php echo isset($_POST['txtqualification']) ? htmlspecialchars($_POST['txtqualification']) : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row d-flex flex-wrap mt-4">
                                        <div class="col-md-12 form-group">
                                            <button name="btnsubmit" type="submit" class="btn btn-primary btn-lg">Register</button>
                                            <p class="mt-3">Already have an account? <a href="Login.php">Click here to login</a></p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script src="../app-assets/vendors/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="../app-assets/vendors/js/jqBootstrapValidation.js" type="text/javascript"></script>
    <script src="../app-assets/js/form-validation.js" type="text/javascript"></script>
    <script src="../plugin/TelPlugin/build/js/intlTelInput.min.js"></script>
    <script src="../plugin/TelPlugin/build/js/intlTelInput-jquery.min.js"></script>
    <script src="../app-assets/vendors/js/switchery.min.js" type="text/javascript"></script>
    <script src="../app-assets/js/switch.min.js" type="text/javascript"></script>
    <script src="../js/doublesided.js"></script>
    
    <script>
    $(document).ready(function() {
        // Form validation
        $('#regForm').validate({
            rules: {
                txtfullname: {
                    required: true,
                    minlength: 3
                },
                txtphone: {
                    required: true,
                    digits: true,
                    minlength: 10
                },
                cmdsex: {
                    required: true
                },
                txtemail: {
                    required: true,
                    email: true
                },
                txtpassword: {
                    required: true,
                    minlength: 6,
                    maxlength: 12,
                    strongPassword: true
                },
                cmddept: {
                    required: true
                },
                cmdemployeetype: {
                    required: true
                },
                txtbasic_salary: {
                    required: true,
                    number: true,
                    min: 0
                },
                txtgross_pay: {
                    required: true,
                    number: true,
                    min: 0
                },
                txtdate_appointment: {
                    required: true,
                    date: true
                }
            },
            messages: {
                txtfullname: {
                    required: "Please enter your full name",
                    minlength: "Name must be at least 3 characters"
                },
                txtphone: {
                    required: "Please enter your phone number",
                    digits: "Please enter only digits",
                    minlength: "Phone number must be at least 10 digits"
                },
                cmdsex: {
                    required: "Please select your gender"
                },
                txtemail: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                },
                txtpassword: {
                    required: "Please enter a password",
                    minlength: "Password must be at least 6 characters",
                    maxlength: "Password cannot be more than 12 characters"
                },
                cmddept: {
                    required: "Please select a department"
                },
                cmdemployeetype: {
                    required: "Please select employee type"
                },
                txtbasic_salary: {
                    required: "Please enter basic salary",
                    number: "Please enter a valid number",
                    min: "Salary cannot be negative"
                },
                txtgross_pay: {
                    required: "Please enter gross pay",
                    number: "Please enter a valid number",
                    min: "Gross pay cannot be negative"
                },
                txtdate_appointment: {
                    required: "Please select appointment date",
                    date: "Please enter a valid date"
                }
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('error-message');
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // Custom method for strong password validation
        $.validator.addMethod("strongPassword", function(value, element) {
            return this.optional(element) || 
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/.test(value);
        }, "Password must contain at least one uppercase letter, one lowercase letter, and one number.");

        // Phone number input formatting
        $("#txtphone").intlTelInput({
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "us";
                    callback(countryCode);
                });
            },
            utilsScript: "../plugin/TelPlugin/build/js/utils.js"
        });
    });
    </script>

    <link rel="stylesheet" href="../css/popup_style.css">
    <?php if(!empty($_SESSION['success'])) {  ?>
    <div class="popup popup--icon -success js_success-popup popup--visible">
        <div class="popup__background"></div>
        <div class="popup__content">
            <h3 class="popup__content__title">
                <strong>Success</strong> 
            </h3>
            <p><?php echo $_SESSION['success']; ?></p>
            <p>
                <button class="button button--success" data-for="js_success-popup">Close</button>
            </p>
        </div>
    </div>
    <?php unset($_SESSION["success"]);  
    } ?>
    <?php if(!empty($_SESSION['error'])) {  ?>
    <div class="popup popup--icon -error js_error-popup popup--visible">
        <div class="popup__background"></div>
        <div class="popup__content">
            <h3 class="popup__content__title">
                <strong>Error</strong> 
            </h3>
            <p><?php echo $_SESSION['error']; ?></p>
            <p>
                <button class="button button--error" data-for="js_error-popup">Close</button>
            </p>
        </div>
    </div>
    <?php unset($_SESSION["error"]);  } ?>
    
    <script>
        var addButtonTrigger = function addButtonTrigger(el) {
            el.addEventListener('click', function() {
                var popupEl = document.querySelector('.' + el.dataset.for);
                popupEl.classList.toggle('popup--visible');
            });
        };

        Array.from(document.querySelectorAll('button[data-for]')).forEach(addButtonTrigger);
    </script>
</body>
</html>