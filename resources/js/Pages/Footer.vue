<script setup>
import FooterLayout from '@/Layouts/FooterLayout.vue';
import FooterLink from '@/Components/Footer/FooterLink.vue'
import Logo from '@/Components/Icons/Logo.vue';
</script>
<template>
    <FooterLayout>
        <template #phRepublic>
            <div class="w-full py-2">

                <div class="pt-5">
                    <div class="flex flex-wrap justify-evenly">
                        <a href="https://www.officialgazette.gov.ph/programs/freedom-of-information/">
                            <img src="/img/freedominfo.png" alt="Freedom of Information" class="sm:h-[12rem] h-[7rem]">
                        </a>
                        <img src="/img/republicph.png" alt="Republika ng Pilipinas" class="sm:h-[12rem] h-[7rem]">
                        <a href="https://dict.gov.ph/transparency/">
                            <img src="/img/transparency_seal-01.png" alt="Transparency Seal" class="sm:h-[12rem] h-[7rem]">
                        </a>
                    </div>
                    <div class="border-b-[1px] py-5 sm:text-base text-xs sm:flex-row sm:justify-evenly flex flex-col gap-3 sm:gap-0">
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
                <div class="flex flex-col items-center pt-2">
                    <Logo classes="sm:h-40 h-16 w-auto" />
                    <p class="flex flex-col text-center">
                        <span class="sm:text-xs text-[0.6rem] sm:tracking-[0.36rem] tracking-[0.2rem] leading-[1]">Department of Agriculture</span>
                        <span class="leading-tight font-light sm:text-2xl text-md">Crop Biotechnology Center</span>
                    </p>
                </div>
            </div>
        </template>
        <template #copyright>
            <div class="resp-container py-1 text-center sm:text-sm text-[0.6rem] font-light text-white ">
                <span>Copyright 2023 DA-Crop Biotechnology Center. All rights reserve.</span>
            </div>
        </template>
    </FooterLayout>
</template>
