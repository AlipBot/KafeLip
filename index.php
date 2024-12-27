<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        KAFELIP
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com">
    </script>
    <style>
        .explore-menu:hover,
        .get-started:hover,
        #scrollToTopBtn:hover {
            background-color: #68B0AB;
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

        /* Custom scrollbar styles */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #FAF3DD;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #000;
            border-radius: 6px;
            border: 3px solid #FAF3DD;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #333;
        }

        /* Scroll to top button */
        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 60px;
            right: 20px;
            z-index: 100;
            background-color: #4A7C59;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px 15px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Hover effect for header navigation links */
        .nav a::after,
        .goMenu a::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #4A7C59;
            transition: width 0.3s;
        }

        .nav a:hover::after,
        .goMenu a:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="bg-[#FAF3DD] font-poppins">
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-[#4A7C59] mr-2">
                </i>
                <span class="text-black">
                    Kafe
                </span>
                <span class="text-black">
                    lip
                </span>
            </div>
            <div class="nav flex gap-6 mx-auto">
                <a class="text-black font-medium active:text-[#4A7C59]" href="#">
                    <i class="fas fa-home text-[#4A7C59] mr-1">
                    </i>
                    <span>
                        LAMAN UTAMA
                    </span>
                </a>
                <a class="text-black font-medium" href="#">
                    <i class="fas fa-info-circle text-[#4A7C59] mr-1">
                    </i>
                    <span>
                        INFO
                    </span>
                </a>
            </div>
            <div class="goMenu flex gap-6">
                <a class="text-black font-medium" href="login.php">
                    <i class="fas fa-sign-in-alt text-[#4A7C59] mr-1">
                    </i>
                    <span>
                        LOG MASUK
                    </span>
                </a>
                <a class="text-black font-medium" href="signup.php">
                    <i class="fas fa-user-plus text-[#4A7C59] mr-1">
                    </i>
                    <span>
                        DAFTAR MASUK
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center py-12 px-10">
            <div class="text-content max-w-lg">
                <h1 class="text-5xl lg:text-7xl font-bold text-black">
                    Selamat Datang
                </h1>
                <p class="text-xl text-gray-600 my-6">
                    Are you hungry? Let's go makan roti canai
                </p>
                <div class="buttons flex gap-6">
                    <a class="get-started bg-[#4A7C59] text-white py-3 px-6 rounded-lg font-bold" href="signup.php">
                        DAFTAR MASUK
                    </a>
                    <a class="explore-menu border-2 border-[#4A7C59] text-[#4A7C59] py-3 px-6 rounded-lg font-bold" href="login.php">
                        LOG MASUK
                    </a>
                </div>
            </div>
            <div class="image-content relative mt-10 lg:mt-0">
                <img alt="A bowl of dessert with raspberries on top" class="rounded-full w-80 h-80 object-cover" height="400" src="lib\image\RotiCanai1.jpg" width="400" />
                <div class="rating absolute top-5 right-5 bg-white py-2 px-4 rounded-full shadow-lg flex items-center gap-2">
                    <span class="font-bold">
                        5.0 Ratings
                    </span>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                </div>
                <div class="comment comment-1 absolute bottom-0 left-[-50] bg-white p-[8px] rounded-lg shadow-lg flex items-center gap-2">
                    <img alt="User profile picture" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/lfCa044dRv1EZCbFTQKfe9z1jyrlmQGoQW7QDMs88Oh27O9nA.jpg" width="40" />
                    <div class="text">
                        <div class="name font-bold">
                            Izz
                        </div>
                        <div class="time text-sm text-gray-600">
                            Today at 12.00 PM
                        </div>
                        <div class="message mt-1">
                            Harga yang berpatutan.
                        </div>
                    </div>
                </div>
                <div class="comment comment-2 absolute bottom-12 -right-4 bg-white p-[8px] rounded-lg shadow-lg flex items-center gap-2">
                    <img alt="User profile picture" class="rounded-full w-10 h-10 object-cover" height="40" src="https://storage.googleapis.com/a1aa/image/lfCa044dRv1EZCbFTQKfe9z1jyrlmQGoQW7QDMs88Oh27O9nA.jpg" width="40" />
                    <div class="text">
                        <div class="name font-bold">
                            Rafiq
                        </div>
                        <div class="time text-sm text-gray-600">
                            Today at 1.00 PM
                        </div>
                        <div class="message mt-1">
                            Roti canai paling strong.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto py-12 px-10">
            <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-black">
                Menu Menarik
                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
                </span>
            </h2>
            <div class="flex flex-col gap-6">
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row-reverse items-center">
                    <img alt="Roti Canai with curry" class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="https://storage.googleapis.com/a1aa/image/zIcpfS6kHl1SPyZKaD6AqIs0k7RgajU4iLLohwWTHwV8uTfTA.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:mr-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Canai with Curry
                        </h3>
                        <p class="text-[#151A00]">
                            RM 5.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti Canai is a type of flatbread that originated from India and is popular in Malaysia. It is usually served with curry or dhal.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row items-center">
                    <img alt="Roti Telur with curry" class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="https://storage.googleapis.com/a1aa/image/DQyqABOkba6OAZS8e1h9kfdovEEM0Bt2eogDJwXdXtSt7O9nA.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Telur with Curry
                        </h3>
                        <p class="text-[#151A00]">
                            RM 6.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti Telur is a variation of Roti Canai that includes an egg. It is also served with curry or dhal.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row-reverse items-center">
                    <img alt="Roti Tisu with condensed milk" class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="https://storage.googleapis.com/a1aa/image/Hq3vfKd3wFTvSiXj6buCnRfPYuHgWUh1nVvTsfVv4Mvp7O9nA.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:mr-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Tisu with Condensed Milk
                        </h3>
                        <p class="text-[#151A00]">
                            RM 4.50
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti Tisu is a thinner and crispier version of Roti Canai, often served with condensed milk.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row items-center">
                    <img alt="Roti Bom with sugar" class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="https://storage.googleapis.com/a1aa/image/fpfddUvlJYm3M0fXcDyrAbve4U9i8AJ17i9uFee4eBdm5uTfTA.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Bom with Sugar
                        </h3>
                        <p class="text-[#151A00]">
                            RM 5.50
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti Bom is a thicker version of Roti Canai, sprinkled with sugar for a sweet taste.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row-reverse items-center">
                    <img alt="Roti Sardin with curry" class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="https://storage.googleapis.com/a1aa/image/9aSbm2H9UmZiC943VsPBFJflGnxhLdILsJ3VlbyHvDp8uTfTA.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:mr-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Sardin with Curry
                        </h3>
                        <p class="text-[#151A00]">
                            RM 7.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti Sardin is a variation of Roti Canai filled with sardines, served with curry.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row items-center">
                    <img alt="Roti Planta with sugar" class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="https://storage.googleapis.com/a1aa/image/AoNXHGfDeChI9EtbBBIG4B9OpPPMtGKBV6HQN3nV29yf7O9nA.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Planta with Sugar
                        </h3>
                        <p class="text-[#151A00]">
                            RM 5.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti Planta is a variation of Roti Canai made with margarine and sprinkled with sugar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto py-12 px-10">
            <div class="bg-[#A1CCA5] p-8 rounded-lg">
                <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-[#151A00]">
                    Fakta Menarik Tentang Roti Canai
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
                    </span>
                </h2>
                <ul class="list-disc list-inside text-lg text-[#151A00]">
                    <li class="mb-4">
                        Roti canai adalah sejenis roti leper yang berasal dari India dan popular di Malaysia.
                    </li>
                    <li class="mb-4">
                        Ia biasanya dihidangkan dengan kuah kari atau dhal.
                    </li>
                    <li class="mb-4">
                        Roti canai juga dikenali sebagai "roti prata" di Singapura.
                    </li>
                    <li class="mb-4">
                        Terdapat pelbagai variasi roti canai seperti roti telur, roti tisu, dan roti bom.
                    </li>
                    <li class="mb-4">
                        Proses membuat roti canai melibatkan menguli dan melipat doh berulang kali untuk mendapatkan tekstur yang lembut dan berlapis.
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer class="w-full bg-[#FAF3DD] text-black py-6 px-10">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
            <div class="mb-4 lg:mb-0">
                © 2023 KAFELIP. All rights reserved.
            </div>
            <div class="flex gap-6">
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-facebook-f">
                    </i>
                </a>
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-twitter">
                    </i>
                </a>
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-instagram">
                    </i>
                </a>
            </div>
        </div>
    </footer>
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up">
        </i>
    </button>
    <script>
        // Show or hide the scroll to top button
        window.onscroll = function() {
            var scrollToTopBtn = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // Scroll to top function
        function scrollToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>

</html>