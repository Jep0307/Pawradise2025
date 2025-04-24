<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pet Management</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>

    <section class="pet-management-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>STAFF-ADMINS MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Staff Admins Management</h1>
                    <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <select name="sort" id="" class="sort-input">
                        <option value="sort" disabled selected>Sort</option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="newest">Newest</option>
                    </select>
                    <a href="../components/add-pet.html" class="add-btn">Add User</a>
                </div>
            </div>

            <div class="search-input">
                <input type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>

            <div class="management-bottom-panel">
                <table class="general-table">
                    <tr>
                        <th>ID</th>
                        <th>IMAGE</th>
                        <th>USERNAME</th>
                        <th>NAME</th>
                        <th>BIO</th>
                        <th>EMAIL</th>
                        <th>PASSWORD</th>
                        <th>PHONE</th>
                        <th>LOCATION</th>
                        <th>CREATED</th>
                        <th></th>
                    </tr>

                    <tr>
                        <td>1</td>
                        <td>
                            <img src="../images/user1.jpg" alt="User Image" width="40" />
                        </td>
                        <td>bruno_doggo</td>
                        <td>Bruno</td>
                        <td>Playful dog ready for adoption</td>
                        <td>bruno@example.com</td>
                        <td>••••••••</td>
                        <td>+1 234 567 8901</td>
                        <td>Los Angeles, CA</td>
                        <td>2024-12-01</td>
                        <td class="options-btn">
                            <span class="material-symbols-outlined">edit</span>
                            <div class="pop-up">
                                <a href="#"><span class="material-symbols-outlined">edit</span>Edit</a>
                                <a href="#"><span class="material-symbols-outlined">delete</span>Delete</a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>
                            <img src="../images/user2.jpg" alt="User Image" width="40" />
                        </td>
                        <td>catlover22</td>
                        <td>Luna</td>
                        <td>Sweet cat rescued from the street</td>
                        <td>luna@example.com</td>
                        <td>••••••••</td>
                        <td>+1 555 888 1122</td>
                        <td>New York, NY</td>
                        <td>2024-11-10</td>
                        <td class="options-btn">
                            <span class="material-symbols-outlined">edit</span>
                            <div class="pop-up">
                                <a href="#"><span class="material-symbols-outlined">edit</span>Edit</a>
                                <a href="#"><span class="material-symbols-outlined">delete</span>Delete</a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>
                            <img src="../images/user3.jpg" alt="User Image" width="40" />
                        </td>
                        <td>rabbit_ron</td>
                        <td>Ronnie</td>
                        <td>Friendly rabbit looking for a home</td>
                        <td>ronnie@example.com</td>
                        <td>••••••••</td>
                        <td>+1 789 456 3210</td>
                        <td>Chicago, IL</td>
                        <td>2024-10-25</td>
                        <td class="options-btn">
                            <span class="material-symbols-outlined">edit</span>
                            <div class="pop-up">
                                <a href="#"><span class="material-symbols-outlined">edit</span>Edit</a>
                                <a href="#"><span class="material-symbols-outlined">delete</span>Delete</a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>
                            <img src="../images/user4.jpg" alt="User Image" width="40" />
                        </td>
                        <td>parrot_pete</td>
                        <td>Pete</td>
                        <td>Colorful parrot that loves to talk</td>
                        <td>pete@example.com</td>
                        <td>••••••••</td>
                        <td>+1 222 333 4444</td>
                        <td>Miami, FL</td>
                        <td>2024-09-18</td>
                        <td class="options-btn">
                            <span class="material-symbols-outlined">edit</span>
                            <div class="pop-up">
                                <a href="#"><span class="material-symbols-outlined">edit</span>Edit</a>
                                <a href="#"><span class="material-symbols-outlined">delete</span>Delete</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</body>

</html>