<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import {usePage} from "@inertiajs/vue3";

export default {
    name: "TermsOfUse",
    components: {InfoPageLayout},
    data() {
        return {
            page: usePage(),
        }
    },
    computed: {
        appName(){
            return this.page.props.appName;
        },
        appNameShort(){
            return this.page.props.appNameShort;
        },
        companyName(){
            return this.page.props.companyName;
        },
        companyNameShort(){
            return this.page.props.companyNameShort;
        },
    }
}
</script>

<template>
    <info-page-layout title="Terms of Use">
        <div class="space-y-6 text-gray-800 leading-5 text-justify">
            <p>
                The following terms outline the rules, responsibilities, and expectations for users of the {{appNameShort}}. By accessing or utilizing the system, you agree to comply with these terms. The content and data presented are provided for informational purposes and public use in line with the agency's mandate to promote transparency and public access to government information.
            </p>
            <p>
                The content of this website is for general information and use only. It is subject to change without notice. The {{companyNameShort}} makes no warranty or representation as to the accuracy, completeness, or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and the {{companyNameShort}} expressly excludes liability for any such inaccuracies or errors to the fullest extent permitted by law.
            </p>

           <div>
               <p class="text-lg font-bold">
                   Introduction
               </p>
               <p class="text-lg">
                   This document outlines the rules governing access to and use of the Plant Breeders and Innovators Network (PIN) System, a centralized repository for crop biotechnology data, studies, and research. Your use of the system constitutes acceptance of these terms.
               </p>
           </div>
            <div>
                <p class="text-lg font-bold">
                    User Eligibility
                </p>
                <p class="text-lg">
                    Access to the {{appNameShort}} is restricted to authorized users who meet specific criteria, including affiliation with recognized organizations in the field of crop biotechnology or related fields. Proof of eligibility may be required upon registration.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Account Responsibilities
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li>Users are responsible for maintaining accurate and up-to-date account information.</li>
                    <li>Users must safeguard their username, password, and other credentials to prevent unauthorized access.</li>
                    <li>Users agree not to share accounts or credentials with unauthorized individuals.</li>
                </ul>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Data Privacy and Security
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li>The {{appNameShort}} prioritizes the protection of sensitive biotechnology data. Users are prohibited from accessing or disclosing such data without proper authorization.</li>
                    <li>Users must comply with all applicable data protection laws, including GDPR and CCPA.</li>
                    <li>The system employs measures to ensure data security, but users must also take responsibility for their actions to prevent unauthorized access or data breaches.</li>
                </ul>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Intellectual Property
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li>Ownership of data and content within the PIN System remains with contributing entities or organizations.</li>
                    <li>Users are granted limited access rights to this information, not ownership rights.</li>
                    <li>Users agree not to reverse engineer, modify, or misuse system software or data for illegal purposes.</li>
                </ul>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Usage Restrictions
                </p>
                <ul class="text-lg list-disc list-inside" >
                    <li>Users may not use the PIN System for activities that violate local, state, or federal laws.</li>
                    <li>Unauthorized use of system features, such as unauthorized access or data extraction, is strictly prohibited.</li>
                    <li>Users must respect intellectual property rights and avoid actions that infringe on third-party rights.</li>
                </ul>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Termination Clause
                </p>
                <p class="text-lg">
                    The organization reserves the right to terminate access for users who violate these terms or associated policies. Violations may lead to legal action and/or delisting from the system.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Governing Law
                </p>
                <p class="text-lg">
                    This Terms of Use document is governed by and construed in accordance with the laws of Philippines, without regard to conflict of laws principles.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Changes to Terms
                </p>
                <p class="text-lg">
                    The organization reserves the right to modify or update these terms at any time. Continued use of the system after changes constitutes acceptance of the new terms.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Compliance with Laws
                </p>
                <p class="text-lg">
                    Users agree to comply with all applicable laws and regulations, including those related to biotechnology research and data handling.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Disclaimers of Warranties
                </p>
                <p class="text-lg">
                    The {{appNameShort}} is provided "as-is" without any warranties regarding accuracy, completeness, or functionality. Users assume all risks associated with system use.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Liability Limitation
                </p>
                <p class="text-lg">
                    While the organization takes measures to ensure system reliability, it cannot be held liable for indirect, incidental, or consequential damages arising from system use.
                </p>
            </div>
            <div>
                <p class="text-lg font-bold">
                    Contact Information
                </p>
                <p class="text-lg">
                    For questions or concerns about these terms, please visit <a href="https://www.facebook.com/DACropBiotechCenter" target="_blank" class="underline">{{ companyName }}</a> or contact at <a href="mailto:cropbiotechcenter@gmail.com" class="underline">cropbiotechcenter@gmail.com</a>.
                </p>
            </div>
        </div>

    </info-page-layout>
</template>

<style scoped>

</style>
