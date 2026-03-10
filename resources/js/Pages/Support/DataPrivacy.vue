<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import {usePage} from "@inertiajs/vue3";

export default {
    name: "DataPrivacy",
    components: {InfoPageLayout},
    data() {
        return {
            page: usePage(),
        }
    },
}
</script>

<template>
    <info-page-layout title="Data Privacy Notice">
        <div class="space-y-6 text-gray-800 leading-5">
            <p>
                This Data Privacy Notice is issued pursuant to the Data Privacy Act of 2012 (Republic Act No. 10173), its Implementing Rules and Regulations, and relevant issuances of the National Privacy Commission. It explains how {{ $appName }} collects, uses, stores, shares, and protects personal data in connection with the {{ $appNameShort }}.
            </p>

            <div>
                <p class="font-semibold">Personal Data Collected</p>
                <p>We collect only data that are necessary and relevant to operate this system, such as:</p>
                <ul class="list-disc list-inside">
                    <li><b>Account and Profile Data:</b> name, email address, role, affiliation, and other registration details.</li>
                    <li><b>System Usage Data:</b> login activity, page interactions, and audit logs necessary for security and service improvement.</li>
                    <li><b>Communications:</b> inquiries or support requests you send to us.</li>
                </ul>
            </div>

            <div>
                <p class="font-semibold">Purpose and Legal Basis for Processing</p>
                <p>We process personal data for the following purposes:</p>
                <ul class="list-disc list-inside">
                    <li>To provide system access, authentication, and account management.</li>
                    <li>To maintain system security, integrity, and auditability.</li>
                    <li>To provide support, respond to requests, and communicate updates.</li>
                    <li>To comply with legal obligations and government requirements.</li>
                </ul>
                <p>Processing is based on the fulfillment of a contract, compliance with legal obligations, and/or the legitimate interests of the organization, as applicable under the Data Privacy Act.</p>
            </div>

            <div>
                <p class="font-semibold">Data Sharing and Disclosure</p>
                <p>We do not sell personal data. We may share data only when necessary, such as:</p>
                <ul class="list-disc list-inside">
                    <li>With service providers or hosting partners under strict confidentiality and security obligations.</li>
                    <li>With authorized government agencies when required by law, regulation, or lawful orders.</li>
                </ul>
            </div>

            <div>
                <p class="font-semibold">Data Retention</p>
                <p>We retain personal data only for as long as necessary to fulfill the purposes stated in this notice and to comply with legal, audit, or archival requirements. Data are securely disposed of when no longer needed.</p>
            </div>

            <div>
                <p class="font-semibold">Data Security</p>
                <p>We implement reasonable and appropriate organizational, physical, and technical measures to protect personal data, including access controls, encryption where appropriate, and monitoring of system activities.</p>
            </div>

            <div>
                <p class="font-semibold">Your Rights as a Data Subject</p>
                <p>Under the Data Privacy Act, you have the right to:</p>
                <ul class="list-disc list-inside">
                    <li>Be informed about the processing of your personal data.</li>
                    <li>Access your personal data and request a copy.</li>
                    <li>Object to processing in certain circumstances.</li>
                    <li>Correct or update inaccurate or incomplete data.</li>
                    <li>Request erasure or blocking of data when legally warranted.</li>
                    <li>Lodge a complaint with the National Privacy Commission.</li>
                    <li>Seek damages as provided under the law.</li>
                </ul>
                <p>Requests may be subject to identity verification and applicable legal limitations.</p>
            </div>

            <div>
                <p class="font-semibold">Changes to this Notice</p>
                <p>We may update this notice to reflect changes in our practices or legal requirements. Updates will be posted on this page with the latest revision date.</p>
            </div>

            <div>
                <p class="font-semibold">Contact Information</p>
                <div>
                    For privacy concerns, requests, or complaints, contact us at <a href="mailto:cropbiotechcenter@gmail.com" class="underline">cropbiotechcenter@gmail.com</a>.
                </div>
            </div>
        </div>
    </info-page-layout>
</template>

<style scoped>

</style>
