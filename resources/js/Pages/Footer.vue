<script setup>
import FooterLayout from '@/Layouts/FooterLayout.vue';
import FooterLink from '@/Components/Footer/FooterLink.vue'
import Logo from '@/Components/Icons/Logo.vue';
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
import {Link} from "@inertiajs/vue3";
</script>
<template>
    <div class="resp-container text-center flex flex-col justify-center items-center select-none py-5 my-5 sm:my-10 lg:text-[1.4rem] md:text-[1.2rem] sm:text-[1rem] text-[0.8rem] font-normal text-gray-900 drop-shadow space-y-4">
        <div>
            Visit our official website at&nbsp;<a href="https://dacbc.philrice.gov.ph" class="underline">www.dacbc.philrice.gov.ph</a>
        </div>
    </div>
    <FooterLayout>
        <template #contactUs>
            <div class="flex flex-col lg:flex-row w-full justify-center gap-2 sm:gap-5 text-gray-100 my-5">
                <div class="flex flex-col gap-2 items-center text-center justify-center py-5">
                    <div class="text-sm leading-tight my-2">Developed by</div>
                    <img src="/img/logo.png" alt="Crop Biotechnology Center Logo" class="sm:h-32 h-24 w-auto drop-shadow">
                    <span class="uppercase sm:text-2xl text-lg leading-tight">{{ $companyName }}</span>
                </div>
                <div class="border-r-4"></div>
                <div class="flex flex-col gap-2 py-5">
                    <label class="text-lg font-bold">Contact Us </label>
                    <div class="flex items-center gap-2 p-5 bg-gray-200 rounded-md bg-opacity-25 shadow text-sm leading-tight">
                        <div>
                            <a class="duration-300 hover:text-[#1877F2]" href="https://www.facebook.com/DACropBiotechCenter" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"></path>
                                </svg>
                            </a>
                        </div>
                        <div>
                            <a class="duration-300 hover:text-[#E1306C]" href="https://www.instagram.com/da_cbcph/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                                </svg>
                            </a>
                        </div>
                        <div>
                            <a class="duration-300 hover:text-cbc-yellow" href="https://cbc360tour.philrice.gov.ph/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-badge-vr-fill" viewBox="0 0 16 16">
                                    <path d="M9.673 5.933v1.938h1.033c.66 0 1.068-.316 1.068-.95 0-.64-.422-.988-1.05-.988z"/>
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.937 7 1.99-5.999H6.61L5.277 9.708H5.22L3.875 5.001H2.5L4.508 11zM8.5 5.001V11h1.173V8.763h1.064L11.787 11h1.327L11.91 8.583C12.455 8.373 13 7.779 13 6.9c0-1.147-.773-1.9-2.105-1.9H8.5z"/>
                                </svg>
                            </a>
                        </div>
                        <div>
                            <ul>
                                <li>Mobile No. 0908 889 7135</li>
                                <li>Email: <a href="mailto:cropbiotechcenter@gmail.com" target="_blank">cropbiotechcenter@gmail.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t my-2"></div>
                    <div class="flex flex-col leading-tight text-sm">
                        <span>PhilRice Compound, Maligaya</span>
                        <span>MV9R+M7M, Science City of Muñoz</span>
                        <span>Nueva Ecija, Philippines 3119</span>
                    </div>
                </div>
            </div>
        </template>
        <template #phRepublic>
            <div class="w-full py-2 text-gray-100">
                <div>
                    <div class="flex flex-wrap items-center justify-evenly my-8 sm:my-10 md:my-14 lg:my-16 opacity-50">
                        <a href="https://dict.gov.ph/transparency/">
                            <img src="/img/transparency_seal-01.png" alt="Transparency Seal" class="h-[7rem] sm:h-[12rem] md:h-[10rem] lg:h-[12rem]">
                        </a>
                        <img src="/img/republicph.png" alt="Republika ng Pilipinas" class="h-[7rem] sm:h-[15rem] md:h-[12rem] lg:h-[15rem]">
                        <a href="https://www.officialgazette.gov.ph/programs/freedom-of-information/">
                            <img src="/img/freedominfo.png" alt="Freedom of Information" class="h-[7rem] sm:h-[15rem] md:h-[12rem] lg:h-[15rem]">
                        </a>
                    </div>
                    <div class="py-5 sm:flex-row sm:justify-evenly flex flex-col gap-3 sm:gap-0">
                        <ul>
                            <FooterLink class="font-semibold" text="Republic of the Philippines" />
                            <FooterLink text="Official Gazette" link="https://www.officialgazette.gov.ph/" />
                            <FooterLink text="Office of the President" link="https://op-proper.gov.ph/" />
                            <FooterLink text="Official Directory" link="https://www.gov.ph/the-government/directory-of-departments-and-agencies/" />
                            <FooterLink text="Official Calendar" link="https://www.officialgazette.gov.ph/nationwide-holidays/2023/" />
                        </ul>
                        <ul>
                            <FooterLink class="font-semibold" text="Executive" />
                            <FooterLink text="Office of the President" link="https://op-proper.gov.ph/" />
                            <FooterLink text="Office of the Vice President" link="https://ovp.gov.ph/" />
                            <FooterLink text="Department of Education" link="https://www.deped.gov.ph/" />
                            <FooterLink text="Department of Interior and Local Development" link="https://www.dilg.gov.ph/" />
                            <FooterLink text="Department of Finance" link="https://www.dof.gov.ph/" />
                            <FooterLink text="Department of Health" link="https://doh.gov.ph/" />
                            <FooterLink text="Department of Science and Technology" link="https://www.dost.gov.ph/" />
                            <FooterLink text="Department of Trade and Industry" link="https://www.dti.gov.ph/" />
                        </ul>
                        <ul>
                            <FooterLink class="font-semibold" text="Legislative" />
                            <FooterLink text="Senate of the Philippines" link="https://legacy.senate.gov.ph/" />
                            <FooterLink text="House of Representative" link="https://congress.gov.ph/" />
                        </ul>
                        <ul>
                            <FooterLink class="font-semibold" text="Judiciary" />
                            <FooterLink text="Supreme Court" link="http://sc.judiciary.gov.ph/" />
                            <FooterLink text="Court of Appeals" link="https://ca.judiciary.gov.ph/" />
                            <FooterLink text="Sandiganbayan" link="https://sb.judiciary.gov.ph/" />
                            <FooterLink text="Court of Tax Appeal" link="https://cta.judiciary.gov.ph/" />
                            <FooterLink text="Judicial Bar and Council" link="https://jbc.judiciary.gov.ph/" />
                        </ul>
                    </div>
                </div>
            </div>
        </template>
        <template #privacyNotice>
            <div class="flex flex-wrap gap-2 justify-evenly border-t-[1px] resp-container px-2 py-2 text-center sm:text-sm text-[0.6rem] font-light text-gray-100">
                <Link :href="route('login')" :class="{ active: route().current('login') }" class="info-page-link sm:text-sm text-[0.8rem]">Login</Link>
                <Link :href="route('register')" :class="{ active: route().current('register') }" class="info-page-link sm:text-sm text-[0.8rem]">Register</Link>
                <info-page-links />
            </div>
        </template>
    </FooterLayout>
</template>
