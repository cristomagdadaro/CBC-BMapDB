<script setup>
import FooterLayout from '@/Layouts/FooterLayout.vue';
import FooterLink from '@/Components/Footer/FooterLink.vue'
import Logo from '@/Components/Icons/Logo.vue';
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
import {Link} from "@inertiajs/vue3";
</script>
<template>
    <div class="resp-container text-center flex flex-col justify-center items-center  py-5 my-5 sm:my-10 lg:text-[1.4rem] md:text-[1.2rem] sm:text-[1rem] text-[0.8rem] font-normal text-gray-900 drop-shadow space-y-4">
        <div>
            Visit our official website at&nbsp;<a href="https://dacbc.philrice.gov.ph" class="underline">www.dacbc.philrice.gov.ph</a>
        </div>
    </div>
    <FooterLayout>
        <template #contactUs>
            <div class="flex flex-col lg:flex-row w-full justify-center gap-2 sm:gap-5 text-gray-100 my-5">
                <div class="flex flex-col gap-2 w-full">
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
                            <a class="duration-300 hover:text-[#E1306C]" href="https://dacbc.philrice.gov.ph/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
                                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484q-.121.12-.242.234c-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z"/>
                                </svg>
                            </a>
                        </div>
                        <div>
                            <ul>
                                <li>Mobile No. 0908 889 7135</li>
                                <li>Email: <a href="mailto:cropbiotechcenter@gmail.com" target="_blank">cropbiotechcenter@gmail.com</a></li>
                                <li class="flex leading-tight text-sm">
                                    Address: PhilRice Compound, Maligaya MV9R+M7M, Science City of Muñoz, Nueva Ecija, Philippines 3119
                                </li>
                            </ul>
                        </div>
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
