import axios from 'axios';

class DashboardService {
    /**
     * Get system-wide statistics
     */
    async getSystemStats() {
        try {
            const response = await axios.get('/api/dashboard/system-stats');
            return response.data;
        } catch (error) {
            console.error('Error fetching system stats:', error);
            throw error;
        }
    }

    /**
     * Get online users (Admin only)
     */
    async getOnlineUsers() {
        try {
            const response = await axios.get('/api/dashboard/online-users');
            return response.data;
        } catch (error) {
            console.error('Error fetching online users:', error);
            throw error;
        }
    }

    /**
     * Get recent user registrations (Admin only)
     */
    async getRecentUsers() {
        try {
            const response = await axios.get('/api/dashboard/recent-users');
            return response.data;
        } catch (error) {
            console.error('Error fetching recent users:', error);
            throw error;
        }
    }

    /**
     * Get user role distribution (Admin only)
     */
    async getUserRoleDistribution() {
        try {
            const response = await axios.get('/api/dashboard/user-role-distribution');
            return response.data;
        } catch (error) {
            console.error('Error fetching user role distribution:', error);
            throw error;
        }
    }

    /**
     * Get system activities (user registrations and logins)
     */
    async getSystemActivities() {
        try {
            const response = await axios.get('/api/dashboard/system-activities');
            return response.data;
        } catch (error) {
            console.error('Error fetching system activities:', error);
            throw error;
        }
    }

    /**
     * Update user activity timestamp
     */
    async updateActivity() {
        try {
            const response = await axios.post('/api/dashboard/activity');
            return response.data;
        } catch (error) {
            console.error('Error updating activity:', error);
            throw error;
        }
    }
}

export default new DashboardService();

