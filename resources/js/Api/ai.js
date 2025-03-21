import { ref } from 'vue';
import axios from 'axios';

export function useAI() {
    const response = ref('');
    const isLoading = ref(false);
    const error = ref(null);
    const conversationHistory = ref([]);

    const sendPrompt = async (prompt, projectId) => {
        isLoading.value = true;
        error.value = null;

        try {
            // Add the new message to conversation history
            conversationHistory.value.push({
                role: 'user',
                content: prompt
            });

            // Create the base context
            let enrichedPrompt = `You are an AI chatbot for a E-Commerce website named DRM Roofing, Glass, and Iron Works and
                your name is Chatter.
                You are a helpful assistant that can help the customers with their needs like navigation, products, categories, and suggestion
                for what they want to do to the products they want to buy.
                You are also able to help the user with their questions and concerns about the products they want to buy.
                You are unable to help about outside topics of the website or anything that is not related to the products they want to buy.

                WEBSITE CONTEXT:
                Navigation:
                - Home
                - Products
                - About Us
                - Contact Us
                There is a search bar in the header navigation bar.
                Login Button in the right side of the header navigation bar if the user is not logged in.
                If the user is logged in, the login button will be replaced with the user's name with a dropdown menu for logout button.
                There is a cart icon in the right side of the header navigation bar.

                Home Page Sections:
                - Hero Section
                - Features Section
                - Latest Products Section
                - Categories Section
                - Explore Our Products Section
                - Testimonials Section
                - Footer

                Products Page Features:
                - Price range filter
                - Brand filter
                - Category filter
                - Availability filter

                CONVERSATION HISTORY:
                ${conversationHistory.value
                    .map(msg => `${msg.role === 'user' ? 'Customer' : 'Chatter'}: ${msg.content}`)
                    .join('\n')}

                Remember to:
                1. Maintain context from the conversation history
                2. Be friendly and helpful
                3. Stay focused on the website and products
                4. Ask follow-up questions when needed for clarity
                5. Provide specific product or navigation suggestions based on the customer's needs

                Now please respond to the customer's latest message.`;

            // Send the enriched prompt to the AI
            const { data } = await axios.post('/ai/process', {
                prompt: enrichedPrompt
            });

            if (data.error) {
                error.value = data.error;
                return null;
            }

            const aiResponse = data.candidates?.[0]?.content?.parts?.[0]?.text;
            if (aiResponse) {
                // Add AI response to conversation history
                conversationHistory.value.push({
                    role: 'assistant',
                    content: aiResponse
                });
                response.value = aiResponse;
                return aiResponse;
            } else {
                error.value = 'No response from AI';
                return null;
            }
        } catch (err) {
            console.error('AI Error:', err);
            error.value = err.response?.data?.error || 'Error processing AI request';
            return null;
        } finally {
            isLoading.value = false;
        }
    };

    // Add method to clear conversation history
    const clearConversation = () => {
        conversationHistory.value = [];
    };

    return { response, isLoading, error, sendPrompt, clearConversation };
}
