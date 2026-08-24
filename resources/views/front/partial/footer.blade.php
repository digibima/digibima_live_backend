<?php
$data = App\Models\Sites::find(getconstant('FOOTER.FOOTER'));
?>
<style>
    .container-fluid .px-5 p span {

        background: #2a2a2a !important;
    }
</style>
<footer>
    <div id="footrow" class="container-fluid">
        <div class="row py-1">
            <div class="col-md-12">
                <div class="px-5">
                    {{-- <p>Digibima Insurance Web Aggregators Private Limited | CIN: U67110RJ2022PTC080500 | IRDAI License
                        No.: IRDAI/INT/WBA/76/2023 Valid till: "09/08/2026"
                        Registered Office - 706 Gali no 6, New Sanganer Road, Jaipur, Rajasthan - 302019. Email ID:
                        info@digibima.com</p>
                    <p>Insurance is the subject matter of solicitation. Information available on this portal is of the
                        partner insurer with whom we have an agreement. The information displayed is solely based on the
                        information received from the respective insurer. The visitors/prospects details may be shared
                        with partner insurers.</p>
                    <p>The information provided on this website/page is for information purpose only. Digibima does not
                        in any form or manner endorse the information so provided on the website and strives to provide
                        factual and unbiased information to customers to assist in making informed insurance choices.
                    </p>
                    <p>&copy; 2024 DIGI BIMA. All Rights Reserved. Powered By Proactive</p> --}}

                    {!! $data->personal_info !!}
                </div>
            </div>
        </div>
    </div>
</footer>
