<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import {usePage} from "@inertiajs/vue3";

export default {
    name: "PrivacPolicy",
    components: {InfoPageLayout},
    data() {
        return {
            page: usePage(),
        }
    },
}
</script>

<template>
    <info-page-layout title="Privacy Policy">
        <div class="space-y-6 text-gray-800 leading-5">
            <p class="text-lg">
                Welcome to the {{ $appName }}. Your use of this system is subject to our Privacy Policy, which outlines how we collect, store, use, and share your personal data. By accessing or using our services, you agree to the terms outlined in this Privacy Policy.
            </p>
            <div>
                <p class="text-xl font-semibold">
                    Data Collection
                </p>
                <p class="text-lg">
                    We collect information to provide and maintain our services, including but not limited to:
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li><b>Account Information:</b> When you register for an account, we may collect your name, email address, username, password, and other personally identifiable information (PII) as required for system access.</li>
                    <li><b>Usage Data:</b> We may collect data on how you use our system, such as the features you use, pages you visit, and other interaction analytics.</li>
                </ul>
                <p class="text-lg">
                    We will only collect data that is necessary for the operation of the {{ $appNameShort }}. Any additional data collection will be explicitly stated at the time of collection.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Data Storage
                </p>
                <p class="text-lg">
                    Your data may be stored on-site using secure servers managed by our hosting provider. We take reasonable measures to ensure data security, including encryption where appropriate and regular data backups.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Data Usage
                </p>
                <p class="text-lg">
                    We use your data to:
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li>Provide system functionality.</li>
                    <li>Improve service offerings based on usage patterns.</li>
                    <li>Communicate with you regarding system updates, new features, or account-related matters.</li>
                </ul>
                <p class="text-lg">
                    Your data will not be used for unauthorized purposes, nor will it be sold or rented to third parties without your explicit consent.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Data Sharing
                </p>
                <p class="text-lg">
                    We may share your data with:
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li><b>Service Providers:</b> Third-party services we use to maintain the PIN System, such as cloud storage providers and analytics platforms.</li>
                    <li><b>Partners:</b> Collaborating organizations or partners involved in biotechnology research and innovation.</li>
                    <li><b>Legal Requirements:</b> To comply with legal obligations or respond to valid requests from law enforcement agencies.</li>
                </ul>
                <p class="text-lg">
                    We will always ensure that third parties adhere to appropriate data protection standards before sharing your information.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    User Rights
                </p>
                <p class="text-lg">
                    You have the right to:
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li><b>Access:</b> Request access to the data we have about you.</li>
                    <li><b>Correct/Update:</b> Correct or update any inaccurate information.</li>
                    <li><b>Delete:</b> Have your data deleted from our system, provided there are no legal or contractual obligations preventing it.</li>
                    <li><b>Opt-Out:</b> Opt out of certain data uses, such as marketing communications.</li>
                </ul>
                <p class="text-lg">
                    Please contact us at [Your Contact Information] to exercise these rights.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Security Measures
                </p>
                <p class="text-lg">
                    We employ industry-standard security measures to protect your data against unauthorized access, breach, or loss. This includes but is not limited to:
                </p>
                <ul class="text-lg list-disc list-inside">
                    <li><b>Encryption:</b> Encrypting sensitive data both in transit and at rest.</li>
                    <li><b>Access Control:</b> Restricting system access to authorized personnel only.</li>
                    <li><b>Regular Audits:</b> Conducting regular security audits and penetration testing.</li>
                </ul>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Cookies and Tracking Technologies
                </p>
                <p class="text-lg">
                    The {{ $appNameShort }} may use cookies and other tracking technologies (e.g., analytics tools) to enhance user experience and optimize service delivery. These technologies collect non-personally identifiable information, such as usage patterns, device identifiers, and location data. You can disable cookies in your browser settings if you prefer not to receive this data collection.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Changes to Privacy Policy
                </p>
                <p class="text-lg">
                    We may update our Privacy Policy from time to time to reflect changes in our services or legal requirements. We will notify you of significant changes via email or through our platform, and your continued use of the system after such changes constitutes acceptance of the revised policy.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Data Transfer Policies
                </p>
                <p class="text-lg">
                    If we transfer data internationally, we ensure compliance with applicable data protection laws.
                </p>
            </div>
            <div>
                <p class="text-xl font-semibold">
                    Contact Information
                </p>
                <div class="text-lg">
                    For any questions or concerns regarding this Privacy Policy, please contact us at <a href="mailto:cropbiotechcenter@gmail.com" class="underline">cropbiotechcenter@gmail.com</a>
                </div>
            </div>
        </div>
    </info-page-layout>
</template>

<style scoped>

</style>
