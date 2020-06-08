<div class="footer-head" id="footer-head">
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center hidden-lg hidden-md hidden-sm">
            <a class="navbar-toggle collapsed" data-toggle="collapse" href="#footer">
                <span class="bars"></span>
                <span class="sr-only">Toggle navigation</span>     
            </a>
        </div>
        <div id="footer" class="navbar-collapse collapse">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 text-center">
                    <h3>Contact</h3>
                    <ul>
                        <li><a href="{BASE}home/feedback">Feedback</a></li>
                        <li><a href="{BASE}home/bugreport">Bug Report</a></li>
                        <li><a href="{BASE}home/terms">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12 text-center">
                    <div class="social">
                        <i><a href="{BASE}fb"><span class="fa fa-facebook"></span></a></i>
                        <i><a href="{BASE}twitter"><span class="fa fa-twitter"></span></a></i>
                        <i><a href="{BASE}in"><span class="fa fa-linkedin"></span></a></i>
                        <i><a href="{BASE}youtube"><span class="fa fa-youtube"></span></a></i>
                        <i><a href="{BASE}git"><span class="fa fa-github"></span></a></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 pull-right text-center">
                    <h3>Subscribe</h3>
                    <div class="input-append newsletter-box">
                        <form action="{BASE}home/subscribe" method="post" class="form-horizontal">
                            <input type="email" class="full" placeholder="Email" id="email" name="email"><br>
                            <input type="hidden" name="token" value="{TOKEN}">
                            <button name="submit" value="submit" type="submit" class="btn btn-lg btn-primary center-block">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>