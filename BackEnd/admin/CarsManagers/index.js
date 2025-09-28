document.addEventListener("DOMContentLoaded", function() {
                const add_new_car = document.querySelector('.add_new_car');
                const cars = document.querySelector('.cars');

                // إخفاء جميع النماذج
                function hideAll() {
                    add_new_car.style.display = 'none';
                    cars.style.display = 'none';
                }

                // عند الضغط على "Update Password"
                document.querySelector('.show-add-car').addEventListener('click', function(e) {
                    e.preventDefault();
                    hideAll();
                    add_new_car.style.display = 'block';
                });

                // عند الضغط على "Add a new admins"
                document.querySelector('.show-cars-availbles').addEventListener('click', function(e) {
                    e.preventDefault();
                    hideAll();
                    cars.style.display = 'grid';
                });

            });