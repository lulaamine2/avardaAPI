// Mock response from the API
const mockApiResponse = () => {
    return {
        success: true,
        message: 'Payment processed successfully'
    };
};

// Simulate a POST request to the payments API
const simulateApiCall = (data) => {
    console.log('Sending data to the payment API:', data);

    return new Promise((resolve) => {
        setTimeout(() => resolve(mockApiResponse()), 1000); // simulate a network delay
    });
};

// Function to call when the user submits the form
const processPayment = () => {
    const cartItems = [
        { productId: '001', description: 'SCA Pellets 15kg', price: 299, taxRate: 0.25, quantity: 2 },
        { productId: '002', description: 'SCA Tändblock', price: 49, taxRate: 0.25, quantity: 1 }
    ];

    const itemsForApi = cartItems.map(item => ({
        description: item.description,
        notes: `Artikelnummer: ${item.productId}`,
        amount: item.price * 100, // Assuming price is in SEK and needs to be in cents
        taxCode: `TAX${item.productId}`,
        taxAmount: Math.round(item.price * item.taxRate * 100), // Calculate tax in cents
        quantity: item.quantity
    }));

    const data = {
        items: itemsForApi,
        checkoutSetup: {
        },
        b2C: {
        }
    };

    simulateApiCall(data)
        .then(response => {
            console.log('API Response:', response);
            if (response.success) {
                alert('Payment successful!');
            } else {
                alert('Payment failed: ' + response.message);
            }
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
};
