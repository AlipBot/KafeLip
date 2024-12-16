<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAFELIP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .explore-menu:hover {
            background-color: #588517;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        @media (max-width: 768px) {
            .nav a span {
                display: none;
            }
            .comment {
                padding: 2px;
                gap: 2px;
            }
            .comment img {
                width: 20px;
                height: 20px;
            }
            .comment .text .name {
                font-size: 12px;
            }
            .comment .text .time {
                font-size: 10px;
            }
            .comment .text .message {
                font-size: 10px;
            }
        }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .fade-in-box {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .fade-in-box.visible {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-[#DAD7CD] font-roboto">
    <div class="w-full bg-[#3A5A40] fade-in">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-[#DAD7CD] mr-2"></i>
                <span class="text-[#588157]"> Kafe</span> <span class="text-[#DAD7CD]">lip</span>
            </div>
            <div class="nav flex gap-6">
                <a class="text-[#fff] font-medium active:text-[#588157]" href="#">
                    <i class="fas fa-home mr-1"></i>
                    <span>LAMAN UTAMA</span>
                </a>
                <a class="text-[#fff] font-medium" href="#">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span>INFO</span>
                </a>
                <a class="text-[#fff] font-medium" href="login.php">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    <span>LOG MASUK</span>
                </a>
                <a class="text-[#fff] font-medium" href="signup.php">
                    <i class="fas fa-user-plus mr-1"></i>
                    <span>DAFTAR MASUK</span>
                </a>
            </div>
        </div>
    </div>
    <div class="w-full bg-[#588157] fade-in">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center py-12 px-10">
            <div class="text-content max-w-lg">
                <h1 class="text-4xl lg:text-6xl font-bold text-white">Selamat Datang</h1>
                <p class="text-lg text-gray-200 my-6">Are you hungry? Let's go makan roti canai</p>
                <div class="buttons flex gap-6">
                    <a class="get-started bg-[#344E41] text-white py-3 px-6 rounded-lg font-bold" href="signup.php">DAFTAR MASUK</a>
                    <a class="explore-menu border-2 border-[#344E41] text-[#fff] py-3 px-6 rounded-lg font-bold" href="login.php">LOG MASUK</a>
                </div>
            </div>
            <div class="image-content relative mt-10 lg:mt-0">
                <img alt="A bowl of dessert with raspberries on top" class="rounded-full w-80 h-80 object-cover" height="400" src="https://storage.googleapis.com/a1aa/image/gKAR7oJ0S6bzBNz5sOqFzgPvfeRxBcXTKf50mxLkfEiyyCsPB.jpg" width="400"/>
                <div class="rating absolute top-5 right-5 bg-white py-2 px-4 rounded-full shadow-lg flex items-center gap-2">
                    <span class="font-bold">5.0 Ratings</span>
                    <i class="fas fa-star text-orange-500"></i>
                    <i class="fas fa-star text-orange-500"></i>
                    <i class="fas fa-star text-orange-500"></i>
                    <i class="fas fa-star text-orange-500"></i>
                    <i class="fas fa-star text-orange-500"></i>
                </div>
                <div class="comment comment-1 absolute bottom-0 left-[-50] bg-white p-[8px] rounded-lg shadow-lg flex items-center gap-2">
                    <img alt="User profile picture" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/Il3hfV448303MKhdbS2x0jCHtSNqYF8xsH3WB1ZLTtHVWg9JA.jpg" width="40"/>
                    <div class="text">
                        <div class="name font-bold">Izz</div>
                        <div class="time text-sm text-gray-600">Today at 12.00 PM</div>
                        <div class="message mt-1">Harga yang berpatutan.</div>
                    </div>
                </div>
                <div class="comment comment-2 absolute bottom-12 -right-4 bg-white p-[8px] rounded-lg shadow-lg flex items-center gap-2">
                    <img alt="User profile picture" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/Il3hfV448303MKhdbS2x0jCHtSNqYF8xsH3WB1ZLTtHVWg9JA.jpg" width="40"/>
                    <div class="text">
                        <div class="name font-bold">Rafiq</div>
                        <div class="time text-sm text-gray-600">Today at 1.00 PM</div>
                        <div class="message mt-1">Roti canai paling strong.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full bg-[#3A5A40] fade-in">
        <div class="container mx-auto py-12 px-10">
            <h2 class="text-3xl font-bold mb-6 relative inline-block text-center w-full text-white">
                Menu Menarik
                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#344E41]"></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-[#DAD7CD] p-6 rounded-lg shadow-lg fade-in-box">
                    <img alt="Roti Canai with curry" class="w-full h-48 object-cover rounded-t-lg" height="300" src="https://storage.googleapis.com/a1aa/image/2qgqp5gOxxr6GxKQpWMfrvxNn3Dju5TYJAe5H7lIo9we3B2nA.jpg" width="400"/>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Roti Canai with Curry</h3>
                        <p class="text-gray-600">RM 5.00</p>
                    </div>
                </div>
                <div class="bg-[#DAD7CD] p-6 rounded-lg shadow-lg fade-in-box">
                    <img alt="Roti Telur with curry" class="w-full h-48 object-cover rounded-t-lg" height="300" src="https://storage.googleapis.com/a1aa/image/F46fR2wG3zTpOy4jek3UEGMmhoJ0oDNH3OL1bJz5u2b87A7TA.jpg" width="400"/>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Roti Telur with Curry</h3>
                        <p class="text-gray-600">RM 6.00</p>
                    </div>
                </div>
                <div class="bg-[#DAD7CD] p-6 rounded-lg shadow-lg fade-in-box">
                    <img alt="Roti Tisu with condensed milk" class="w-full h-48 object-cover rounded-t-lg" height="300" src="https://storage.googleapis.com/a1aa/image/nQ769azgc05tNh9wetaJ1DAQajWW94qfm5mrcmr2syJf3B2nA.jpg" width="400"/>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Roti Tisu with Condensed Milk</h3>
                        <p class="text-gray-600">RM 4.50</p>
                    </div>
                </div>
                <div class="bg-[#DAD7CD] p-6 rounded-lg shadow-lg fade-in-box">
                    <img alt="Roti Bom with sugar" class="w-full h-48 object-cover rounded-t-lg" height="300" src="https://storage.googleapis.com/a1aa/image/hI5NJOBekDSASSWowaqEuTPyBhfBC1M6aJUhr8gP7ZxB8A7TA.jpg" width="400"/>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Roti Bom with Sugar</h3>
                        <p class="text-gray-600">RM 5.50</p>
                    </div>
                </div>
                <div class="bg-[#DAD7CD] p-6 rounded-lg shadow-lg fade-in-box">
                    <img alt="Roti Sardin with curry" class="w-full h-48 object-cover rounded-t-lg" height="300" src="https://storage.googleapis.com/a1aa/image/TBunYwgJROLGFJdh9bu0ozfvBxV04ofdUberAkNF5Gu73B2nA.jpg" width="400"/>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Roti Sardin with Curry</h3>
                        <p class="text-gray-600">RM 7.00</p>
                    </div>
                </div>
                <div class="bg-[#DAD7CD] p-6 rounded-lg shadow-lg fade-in-box">
                    <img alt="Roti Planta with sugar" class="w-full h-48 object-cover rounded-t-lg" height="300" src="https://storage.googleapis.com/a1aa/image/NbuwEvQm3CZNHJCdfp1e6xpVYffH9LP6fbXZmODY7T9SgHYfE.jpg" width="400"/>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold">Roti Planta with Sugar</h3>
                        <p class="text-gray-600">RM 5.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full bg-[#344E41] fade-in">
        <div class="container mx-auto py-12 px-10">
            <h2 class="text-3xl font-bold mb-6 relative inline-block text-center w-full text-white">
                Fakta Menarik Tentang Roti Canai
                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#588157]"></span>
            </h2>
            <ul class="list-disc list-inside text-lg text-gray-200">
                <li class="mb-4">Roti canai adalah sejenis roti leper yang berasal dari India dan popular di Malaysia.</li>
                <li class="mb-4">Ia biasanya dihidangkan dengan kuah kari atau dhal.</li>
                <li class="mb-4">Roti canai juga dikenali sebagai "roti prata" di Singapura.</li>
                <li class="mb-4">Terdapat pelbagai variasi roti canai seperti roti telur, roti tisu, dan roti bom.</li>
                <li class="mb-4">Proses membuat roti canai melibatkan menguli dan melipat doh berulang kali untuk mendapatkan tekstur yang lembut dan berlapis.</li>
            </ul>
        </div>
    </div>
    <footer class="w-full bg-[#3A5A40] text-white py-6 px-10 fade-in">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
            <div class="mb-4 lg:mb-0">© 2023 KAFELIP. All rights reserved.</div>
            <div class="flex gap-6">
                <a class="text-white" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="text-white" href="#"><i class="fab fa-twitter"></i></a>
                <a class="text-white" href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                } else {
                    entry.target.classList.remove('visible');
                }
            });
        });

        document.querySelectorAll('.fade-in').forEach(element => {
            observer.observe(element);
        });

        document.querySelectorAll('.fade-in-box').forEach(element => {
            observer.observe(element);
        });
    </script>
</body>
</html>