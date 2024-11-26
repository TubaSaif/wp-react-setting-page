import React, { useState } from 'react';
import './style.css'; // Include the styling

const Dashboard = () => {
    const [formData, setFormData] = useState({ name: '', email: '' });
    const [statusMessage, setStatusMessage] = useState('');
    const [statusType, setStatusType] = useState(''); // 'success' or 'error'

    // Handle form field changes
    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    // Handle form submission
    const handleSubmit = async (e) => {
        e.preventDefault();

        // Prepare the data to be sent
        const data = new FormData();
        data.append('action', 'my_form_submission');
        data.append('name', formData.name);
        data.append('email', formData.email);

        try {
            // Send data using AJAX (WordPress Ajax Handler)
            const response = await fetch(myAppData.ajaxurl, {
                method: 'POST',
                body: data,
            });

            const result = await response.json();

            // Handle response from WordPress
            if (result.success) {
                setStatusMessage('Form submitted successfully!');
                setStatusType('success');
                setFormData({ name: '', email: '' });

                // Automatically hide the message after 3 seconds
                setTimeout(() => setStatusMessage(''), 3000);
            } else {
                setStatusMessage('Failed to submit form.');
                setStatusType('error');

                // Automatically hide the message after 3 seconds
                setTimeout(() => setStatusMessage(''), 3000);
            }
        } catch (error) {
            setStatusMessage('An error occurred.');
            setStatusType('error');

            // Automatically hide the message after 3 seconds
            setTimeout(() => setStatusMessage(''), 3000);
            console.error('AJAX request failed:', error);
        }
    };

    return (
        <div className="dashboard">
            <h1>Welcome to the Dashboard</h1>
            <form onSubmit={handleSubmit}>
                <div>
                    <label htmlFor="name">Name:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value={formData.name}
                        onChange={handleChange}
                        required
                    />
                </div>

                <div>
                    <label htmlFor="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        required
                    />
                </div>

                <button type="submit">Submit</button>
            </form>

            {/* Status message */}
            {statusMessage && (
                <div className={`status-message ${statusType}`}>
                    {statusMessage}
                </div>
            )}
        </div>
    );
};

export default Dashboard;
