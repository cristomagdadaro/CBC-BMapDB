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
                        <img src="/img/republicph.png" alt="Republika ng Pilipinas" class="sm:h-[12rem] h-[7rem]">
                        <img src="/img/freedominfo.png" alt="Freedom of Information" class="sm:h-[12rem] h-[7rem]">
                    </div>
                    <div class="border-b-[1px] py-5 sm:text-base text-xs sm:flex-row sm:justify-evenly flex flex-col gap-3 sm:gap-0">
                        <ul>
                            <li class="font-bold sm:text-lg">Republic of the Philippines</li>
                            <FooterLink text="Official Gazette" link="#" />
                            <FooterLink text="Office of the President" link="#" />
                            <FooterLink text="Official Directory" link="#" />
                            <FooterLink text="Official Calendar" link="#" />
                        </ul>
                        <ul>
                            <li class="font-bold sm:text-lg">Executive</li>
                            <FooterLink text="Office of the President" link="#" />
                            <FooterLink text="Office of the Vice President" link="#" />
                            <FooterLink text="Department of Education" link="#" />
                            <FooterLink text="Department of Interior and Local Development" link="#" />
                            <FooterLink text="Department of Finance" link="#" />
                            <FooterLink text="Department of Health" link="#" />
                            <FooterLink text="Department of Science and Technology" link="#" />
                            <FooterLink text="Department of Trade and Industry" link="#" />
                        </ul>
                        <ul>
                            <li class="font-bold sm:text-lg">Legislative</li>
                            <FooterLink text="Senate of the Philippines" link="#" />
                            <FooterLink text="House of Representative" link="#" />
                        </ul>
                        <ul>
                            <li class="font-bold sm:text-lg">Judiciary</li>
                            <FooterLink text="Supreme Court" link="#" />
                            <FooterLink text="Court of Appeals" link="#" />
                            <FooterLink text="Sandiganbayan" link="#" />
                            <FooterLink text="Court of Tax Appeal" link="#" />
                            <FooterLink text="Judicial Bar and Council" link="#" />
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
            <div class="resp-container py-1 text-center sm:text-lg text-[0.6rem] font-light text-white ">
                <span>Copyright 2023 DA-Crop Biotechnology Center. All rights reserve.</span>
            </div>
        </template>
    </FooterLayout>
</template>
