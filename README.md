<!-- PROJECT OVERVIEW -->
<div align="center">
  <h3 align="center">AI Image Generation Application</h3>
  <p align="center">
    An innovative solution for creating AI-powered images through user prompts
  </p>
</div>



<!-- PROJECT DETAILS -->
## Project Overview

The AI Image Generation Application, expertly crafted using Laravel, Filament PHP, OpenAI, and Dalle, stands as a sophisticated and user-friendly platform. It harnesses the power of artificial intelligence to transform textual prompts into distinctive and visually appealing images.

### Technology Stack

This application is developed using the following technologies:

- Laravel (Link: [Laravel](https://laravel.com))
- Filament (Link: [Filament](https://filamentphp.com/))
- OpenAI (Link: [OpenAI](https://openai.com/))
- PEST (Link: [PEST](https://pestphp.com/))
  


### System Requirements

To run this Laravel application, ensure you have:

- PHP v8.1 or newer
- Laravel v10.0 or newer
- MySQL

### Setup Process

#### Standard Installation

Follow the official Laravel guide for server prerequisites. [Laravel Documentation](https://laravel.com/docs/10.x)

1. Clone the repository

        git clone https://github.com/Ashikur37/image-generator.git

    
2. Navigate to the repository folder

        cd image-generator
    
3. Install dependencies using composer

        composer install
    
4. Copy and configure the .env file

        cp .env.example .env
    
    
5. Generate a new application key

        php artisan key:generate
    
6. Migrate the database

        php artisan migrate --seed
    
7. Create a storage symlink for public storage

        php artisan storage:link
    
8. Start the server

        php artisan serve
    
9.  Run the queue

        php artisan queue:work

**Reminder: Ensure the OPENAI_API_KEY and AWS credentials are configured in the .env file, especially if using an S3 Bucket.**

Obtain OpenAI API Key:https://platform.openai.com/api-keys

Access the server at http://localhost:8000/admin/login




Ready to use! Log in at /admin/login with:

- **Email:** admin@gmail.com
- **Password:** 12345678


Run the test cases

    ./vendor/bin/pest



