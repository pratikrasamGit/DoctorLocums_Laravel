 <!-- Footer -->
 <div class="dashboard-footer-spacer"></div>
 <div class="small-footer margin-top-15">
     <div class="small-footer-copyrights">
         © 2020 All Rights Reserved | Nurseify, LLC.&nbsp; by <a value="https://www.imc.consulting" type="url"
             href="https://www.imc.consulting" target="_blank" data-runtime-url="https://www.imc.consulting">IMC</a>
     </div>
 </div>
 <ul class="footer-social-links">
     <li>
         <a href="#" title="Facebook" data-tippy-placement="top">
             <i class="icon-brand-facebook-f"></i>
         </a>
     </li>
     <li>
         <a href="#" title="Twitter" data-tippy-placement="top">
             <i class="icon-brand-twitter"></i>
         </a>
     </li>
     <li>
         <a href="#" title="Google Plus" data-tippy-placement="top">
             <i class="icon-brand-google-plus-g"></i>
         </a>
     </li>
     <li>
         <a href="#" title="LinkedIn" data-tippy-placement="top">
             <i class="icon-brand-linkedin-in"></i>
         </a>
     </li>
 </ul>
 <div class="clearfix"></div>
 </div>
 <!-- Footer / End -->
 </div>
 </div>
 <!-- Dashboard Content / End -->
 </div>
 <!-- Dashboard Container / End -->
 </div>
 <!-- Wrapper / End -->
 <!-- Scripts
================================================== -->
 <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
 <script src="{{ asset('js/jquery-migrate-3.3.2.min.js') }}"></script>
 <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
 <script src="{{ asset('js/mmenu.min.js') }}"></script>
 <script src="{{ asset('js/tippy.all.min.js') }}"></script>
 <script src="{{ asset('js/simplebar.min.js') }}"></script>
 <script src="{{ asset('js/bootstrap-slider.min.js') }}"></script>
 <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
 <script src="{{ asset('js/snackbar.js') }}"></script>
 <script src="{{ asset('js/clipboard.min.js') }}"></script>
 <script src="{{ asset('js/counterup.min.js') }}"></script>
 <script src="{{ asset('js/magnific-popup.min.js') }}"></script>
 <script src="{{ asset('js/slick.min.js') }}"></script>
 <script src="{{ asset('js/custom.js') }}"></script>
 @yield('footer_js')
 <script type="text/javascript">
     $(document).ready(function() {
         $('.selectform').select2({
             width: '100%'
         });
     });
 </script>
 <!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
 <script>
     // Snackbar for user status switcher
     $('#snackbar-user-status label').click(function() {
         Snackbar.show({
             text: 'Your status has been changed!',
             pos: 'bottom-center',
             showAction: false,
             actionText: "Dismiss",
             duration: 3000,
             textColor: '#fff',
             backgroundColor: '#383838'
         });
     });
 </script>
 </body>

 </html>
