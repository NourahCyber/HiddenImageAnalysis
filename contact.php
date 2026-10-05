<?php
$pageTitle = "Contact Hidden Insight - Brain Tumor Detection";
include 'assets/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real application, you would process the form data here
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // For demonstration purposes, we'll just print a success message
    $formSubmitted = true;
}
?>

<main class="contact-page">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-8 text-primary">Contact Us</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="contact-form bg-secondary bg-opacity-60 rounded-lg p-6 shadow-lg">
                <?php if (isset($formSubmitted) && $formSubmitted): ?>
                    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                        Thank you for your message. We'll get back to you soon!
                    </div>
                <?php else: ?>
                    <form id="contact-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-primary-foreground mb-2">Name</label>
                            <input type="text" id="name" name="name" required class="w-full px-3 py-2 bg-background text-primary-foreground border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-primary-foreground mb-2">Email</label>
                            <input type="email" id="email" name="email" required class="w-full px-3 py-2 bg-background text-primary-foreground border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-primary-foreground mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" required class="w-full px-3 py-2 bg-background text-primary-foreground border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-primary-foreground mb-2">Message</label>
                            <textarea id="message" name="message" required class="w-full px-3 py-2 bg-background text-primary-foreground border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary h-32"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-primary-foreground py-2 px-4 rounded-md hover:bg-primary-dark transition duration-300">Send Message</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="contact-info bg-secondary bg-opacity-60 rounded-lg p-6 shadow-lg">
                <h2 class="text-2xl font-semibold mb-4 text-primary">Get in Touch</h2>
                <p class="mb-6 text-primary-foreground">Have questions about Hidden Insight or want to learn more about our AI model? We're here to assist you.</p>
            </div>
        </div>
    </div>
</main>

<?php include 'assets/footer.php'; ?>