<script setup>
import FooterLayout from '@/Layouts/FooterLayout.vue';
import FooterLink from '@/Components/Footer/FooterLink.vue'
import Logo from '@/Components/Icons/Logo.vue';
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
</script>
<template>
    <div class="resp-container text-center select-none py-5 my-5 sm:my-10 lg:text-[1.4rem] md:text-[1.2rem] sm:text-[1rem] text-[0.8rem] font-normal text-gray-900 drop-shadow">
        Reach us at&nbsp;<a href="emailTo:cropbiotechcenter@gmail.com" class="underline"> cropbiotechcenter@gmail.com</a>
    </div>
    <FooterLayout>
        <template #phRepublic>
            <div class="w-full py-2 text-gray-100">
                <div class="items-center pt-2 hidden">
                    <Logo classes="sm:h-40 h-16 w-auto" />
                    <p class="flex flex-col text-center">
                        <span class="sm:text-xs text-[0.6rem] sm:tracking-[0.36rem] tracking-[0.2rem] leading-[1]">Department of Agriculture</span>
                        <span class="leading-tight font-light sm:text-2xl text-md">Crop Biotechnology Center</span>
                    </p>
                </div>
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
            <div class="flex flex-wrap justify-center gap-2 sm:gap-5 resp-container px-2 py-2 text-center sm:text-sm text-[0.6rem] font-light text-gray-100">
                <info-page-links />
            </div>
        </template>
        <template #copyright>
            <div class="flex justify-between border-t-[1px] resp-container px-2 py-2 text-center sm:text-sm text-[0.6rem] font-light text-gray-100">
<!--                <span>&copy; Copyright {{ new Date().getFullYear() }}, DA-Crop Biotechnology Center. All rights reserved.</span>-->
                <span class="whitespace-nowrap">{{ $page.props.companyName }}</span>
                <span>Powered by Philippine Rice Research Institute</span>
            </div>
        </template>
    </FooterLayout>
</template>
