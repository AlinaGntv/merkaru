@extends('layouts.app')

@section('content')
    <section class="contacts">
        <div class="container">
            <h2>Свяжитесь с нами</h2>
            <p>Мы всегда рады помочь вам. Свяжитесь с нами удобным для вас способом!</p>

            <div class="contact-details">
                <!-- Адрес -->
                <div class="contact-item">
                    <img src="{{ asset('assets/images/address-icon.png') }}" alt="Адрес">
                    <h3>Адрес</h3>
                    <p>
                        <a href="https://www.google.com/maps/place/%D0%A1%D0%B5%D1%80%D0%B3%D0%B8%D0%B5%D0%B2+%D0%9F%D0%BE%D1%81%D0%B0%D0%B4,+%D0%9D%D0%BE%D0%B2%D0%BE%D1%83%D0%B3%D0%BB%D0%B8%D1%87%D1%81%D0%BA%D0%BE%D0%B5+%D1%88%D0%BE%D1%81%D1%81%D0%B5,+52/" 
                           target="_blank" 
                           class="contact-link">
                            Сергиев Посад, Новоугличское шоссе, 52
                        </a>
                    </p>
                </div>
                
                <!-- Телефон -->
                <div class="contact-item">
                    <img src="{{ asset('assets/images/phone-icon.png') }}" alt="Телефон">
                    <h3>Телефон</h3>
                    <p>
                        <a href="tel:+74951234567" class="contact-link">
                            +7 (495) 123-45-67
                        </a>
                    </p>
                </div>
                
                <!-- Email -->
                <div class="contact-item">
                    <img src="{{ asset('assets/images/email-icon.png') }}" alt="Email">
                    <h3>Email</h3>
                    <p>
                        <a href="mailto:support@merka.ru" class="contact-link">
                            support@merka.ru
                        </a>
                    </p>
                </div>
                
                <!-- Режим работы -->
                <div class="contact-item">
                    <img src="{{ asset('assets/images/clock-icon.png') }}" alt="Часы работы">
                    <h3>Часы работы</h3>
                    <p>Пн-Пт: 09:00 - 18:00<br>Сб-Вс: выходной</p>
                </div>
            </div>

            <div class="map">
                <h3>Мы на карте</h3>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.522656635389!2d37.6203933!3d55.75396!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x414ab6d2e1b2f7c7%3A0xe6b650c94dbf7220!2z0JrRg9C90LjQt9Cw!5e0!3m2!1sru!2sru!4v1698342823666!5m2!1sru!2sru"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
@endsection