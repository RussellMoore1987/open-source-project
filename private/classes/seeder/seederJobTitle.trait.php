<?php
    // ? got Job titles from https://zety.com/blog/job-titles#marketing, https://www.cwjobs.co.uk/JobSearch/database, https://skillcrush.com/blog/41-tech-job-titles/

    // seeder for Job titles 
    trait SeederJobTitle {
        // to get a Job title
        public function job_title(array $arrayNamesForJobTitles=[]) {
            // create default array 
            $useJobTitles = [];
            // construct an array of arrays to pull job titles, if no job titles are in the $arrayNamesForJobTitles array use all job titles from $arrayOfJobTitles
            if (!$arrayNamesForJobTitles) {
                $arrayNamesForJobTitles = $this->arrayOfJobTitles;
            }
            // loop through and check to make sure that the job title arrays exist
            foreach ($arrayNamesForJobTitles as $arrayName) {
                if (isset($this->$arrayName) && is_array($this->$arrayName)) {
                    $useJobTitles[] = $arrayName;
                }
            }
            // if at this point we have an empty array, fill it
            if (!$useJobTitles) {
                $useJobTitles = $this->arrayOfJobTitles;
            }
            // select a job title array
            $jodTitleArrayName = $useJobTitles[rand(0, count($useJobTitles) - 1)];
            // return data
            return $jobTitle = $this->$jodTitleArrayName[rand(0, count($this->$jodTitleArrayName) - 1)]; 
        }

        // an array of job title arrays names, an array of array names
        public $arrayOfJobTitles = [
            'marketing', 'administrative', 'cLevel','cLevelFull','leadership','it','webDevelopment','sales','construction','businessOwner','customerService','operationsBusiness','finance','engineering','researcher','teacher','artistic','healthcare','hospitality','foodService','scientist','phoneService','counseling','cosmetology','writing','labor','animals','driving','volunteer','otherJob','db'
        ];
        
        // job title arrays
        public $marketing = [
            'Marketing Specialist', 'Marketing Manager', 'Marketing Director', 'Graphic Designer', 'Marketing Research Analyst', 'Marketing Communications Manager', 'Marketing Consultant', 'Product Manager', 'Public Relations', 'Social Media Assistant', 'Brand Manager', 'SEO Manager', 'Content Marketing Manager', 'Copywriter', 'Media Buyer', 'Digital Marketing Manager', 'eCommerce Marketing Specialist', 'Brand Strategist', 'Vice President of Marketing', 'Media Relations Coordinator'
        ];

        public $administrative = [
            'Administrative Assistant', 'Receptionist', 'Office Manager', 'Auditing Clerk', 'Bookkeeper', 'Account Executive', 'Branch Manager', 'Business Manager', 'Quality Control Coordinator', 'Administrative Manager', 'Chief Executive Officer', 'Business Analyst', 'Risk Manager', 'Human Resources', 'Office Assistant', 'Secretary', 'Office Clerk', 'File Clerk', 'Account Collector', 'Administrative Specialist', 'Executive Assistant', 'Program Administrator', 'Program Manager', 'Administrative Analyst', 'Data Entry'
        ];

        public $cLevel = ['CEO', 'COO', 'CFO', 'CIO', 'CTO', 'CMO', 'CHRO', 'CDO', 'CPO', 'CCO'];

        public $cLevelFull = [
            'Chief Executive Officer', 'Chief Operating Officer', 'Chief Financial Officer', 'Chief Information Officer', 'Chief Technology Officer', 'Chief Marketing Officer', 'Chief Human Resources Officer', 'Chief Data Officer', 'Chief Product Officer', 'Chief Customer Officer'
        ];

        public $leadership = [
            'Team Leader', 'Manager', 'Assistant Manager', 'Executive', 'Director', 'Coordinator', 'Administrator', 'Controller', 'Officer', 'Organizer', 'Supervisor', 'Superintendent', 'Head Supervisor', 'Overseer', 'Chief Supervisor', 'Foreman', 'Controller', 'Principal', 'President', 'Lead'
        ];

        public $it = [
            'Computer Scientist', 'IT Professional', 'SQL Developer', 'Web Designer', 'Web Developer', 'Help Desk Worker', 'Desktop Support', 'Software Engineer', 'Data Entry', 'DevOps Engineer', 'Computer Programmer', 'Network Administrator', 'Information Security Analyst', 'Artificial Intelligence Engineer', 'Cloud Architect', 'IT Manager', 'Technical Specialist', 'Application Developer', 'Security Specialist'
        ];

        public $webDevelopment = [
            'Web Designer', 'UI/UX Designer', 'UI Designer', 'UX Designer', 'Art Director', 'Web Developer', 'Web Content Strategist', 'Information Technician', 'Product Manager', 'Agile Project Manager', 'Scrum Master', 'Dev. Ops Manager', 'Customer Service Representative', 'SEO Specialist', 'Full-stack JavaScript Developer', 'Front-End Developer', 'Back-End Developer', 'Back-End Testing/QA', 'Developer', 'Engineer', 'Front-End SEO Expert', 'Mobile/Tablet Developer', 'Mobile/Tablet Designer', 'Accessibility Expert', 'Front-End Dev. Ops', 'Front-End Testing/QA', 'Content Manager', 'Interaction Designer', 'Lead Designer', 'Lead Developer', 'Lead Engineer', 'Full-Stack Developer', 'Software Developer', 'WordPress Developer', 'Frameworks Specialist', 'Python Developer', 'React Developer', 'Vue Developer', 'PHP Developer', 'Mobile App Developer', 'Graphic Designer', 'Layout Designer', 'Site Tester', 'JavaScript Developer', 'SEO Expert'
        ];

        public $sales = [
            'Sales Associate', 'Sales Representative', 'Sales Manager', 'Retail Worker', 'Store Manager', 'Sales Representative', 'Sales Manager', 'Real Estate Broker', 'Sales Associate', 'Cashier', 'Store Manager', 'Account Executive', 'Account Manager', 'Area Sales Manager', 'Direct Salesperson', 'Director of Inside Sales', 'Outside Sales Manager', 'Sales Analyst', 'Market Development Manager', 'B2B Sales Specialist', 'Sales Engineer', 'Merchandising Associate'
        ];

        public $construction = [
            'Construction Worker', 'Taper', 'Plumber', 'Heavy Equipment Operator', 'Vehicle or Equipment Cleaner', 'Carpenter', 'Electrician', 'Painter', 'Welder', 'Handyman', 'Boilermaker', 'Crane Operator', 'Building Inspector', 'Pipefitter', 'Sheet Metal Worker', 'Iron Worker', 'Mason', 'Roofer', 'Solar Photovoltaic Installer', 'Well Driller'
        ];

        public $businessOwner = [
            'CEO', 'Proprietor', 'Principal', 'Owner', 'President', 'Founder', 'Administrator', 'Director', 'Managing Partner', 'Managing Member'
        ];

        public $customerService = [
            'Virtual Assistant', 'Customer Service', 'Customer Support', 'Concierge', 'Help Desk', 'Customer Service Manager', 'Technical Support Specialist', 'Account Representative', 'Client Service Specialist', 'Customer Care Associate'
        ];

        public $operationsBusiness = [
            'Operations Manager', 'Operations Assistant', 'Operations Coordinator', 'Operations Analyst', 'Operations Director', 'Vice President of Operations', 'Operations Professional', 'Scrum Master', 'Continuous Improvement Lead', 'Continuous Improvement Consultant'
        ];

        public $finance = [
            'Credit Authorizer', 'Benefits Manager', 'Credit Counselor', 'Accountant', 'Bookkeeper', 'Accounting Analyst', 'Accounting Director', 'Accounts Payable/Receivable Clerk', 'Auditor', 'Budget Analyst', 'Controller', 'Financial Analyst', 'Finance Manager', 'Economist', 'Payroll Manager', 'Payroll Clerk', 'Financial Planner', 'Financial Services Representative', 'Finance Director', 'Commercial Loan Officer'
        ];

        public $engineering = [
            'Engineer', 'Mechanical Engineer', 'Civil Engineer', 'Electrical Engineer', 'Assistant Engineer', 'Chemical Engineer', 'Chief Engineer', 'Drafter', 'Engineering Technician', 'Geological Engineer', 'Biological Engineer', 'Maintenance Engineer', 'Mining Engineer', 'Nuclear Engineer', 'Petroleum Engineer', 'Plant Engineer', 'Production Engineer', 'Quality Engineer', 'Safety Engineer', 'Sales Engineer',  'Software Engineer'
        ];

        public $researcher = [
            'Researcher', 'Research Assistant', 'Data Analyst', 'Business Analyst', 'Financial Analyst', 'Biostatistician', 'Title Researcher', 'Market Researcher', 'Title Analyst', 'Medical Researcher'
        ];

        public $teacher = [
            'Mentor', 'Tutor', 'Online Tutor', 'Teacher', 'Teaching Assistant', 'Substitute Teacher', 'Preschool Teacher', 'Test Scorer', 'Online ESL Instructor', 'Professor', 'Assistant Professor'
        ];

        public $artistic = [
            'Graphic Designer', 'Artist', 'Interior Designer', 'Video Editor', 'Video or Film Producer', 'Playwright', 'Musician', 'Novelist/Writer', 'Computer Animator', 'Photographer', 'Camera Operator', 'Sound Engineer', 'Motion Picture Director', 'Actor', 'Music Producer', 'Director of Photography'
        ];

        public $healthcare = [
            'Nurse', 'Travel Nurse', 'Nurse Practitioner', 'Doctor', 'Caregiver', 'CNA', 'Physical Therapist', 'Pharmacist', 'Pharmacy Assistant', 'Medical Administrator', 'Medical Laboratory Tech', 'Physical Therapy Assistant', 'Massage Therapy', 'Dental Hygienist', 'Orderly', 'Personal Trainer', 'Massage Therapy', 'Medical Laboratory Tech', 'Phlebotomist', 'Medical Transcriptionist', 'Telework Nurse/Doctor', 'Reiki Practitioner'
        ];

        public $hospitality = [
            'Housekeeper', 'Flight Attendant', 'Travel Agent', 'Hotel Front Door Greeter', 'Bellhop', 'Cruise Director', 'Entertainment Specialist', 'Hotel Manager', 'Front Desk Associate', 'Front Desk Manager', 'Concierge', 'Group Sales', 'Event Planner', 'Porter', 'Spa Manager', 'Wedding Coordinator', 'Cruise Ship Attendant', 'Casino Host', 'Hotel Receptionist', 'Reservationist', 'Events Manager', 'Meeting Planner', 'Lodging Manager', 'Director of Maintenance', 'Valet'
        ];

        public $foodService = [
            'Waiter/Waitress', 'Server', 'Chef', 'Fast Food Worker', 'Barista', 'Line Cook', 'Cafeteria Worker', 'Restaurant Manager', 'Wait Staff Manager', 'Bus Person', 'Restaurant Chain Executive'
        ];

        public $scientist = [
            'Political Scientist', 'Chemist', 'Conservation Scientist', 'Sociologist', 'Biologist', 'Geologist', 'Physicist', 'Astronomer', 'Atmospheric Scientist', 'Molecular Scientist'
        ];

        public $phoneService = [
            'Call Center Representative', 'Customer Service', 'Telemarketer', 'Telephone Operator', 'Phone Survey Conductor', 'Dispatcher for Trucks or Taxis', 'Customer Support Representative', 'Over the Phone Interpreter', 'Phone Sales Specialist', 'Mortgage Loan Processor'
        ];

        public $counseling = [
            'Counselor', 'Mental Health Counselor', 'Addiction Counselor', 'School Counselor', 'Speech Pathologist', 'Guidance Counselor', 'Social Worker', 'Therapist', 'Life Coach', 'Couples Counselor'
        ];

        public $cosmetology = [
            'Beautician', 'Hair Stylist', 'Nail Technician', 'Cosmetologist', 'Salon Manager', 'Makeup Artist', 'Esthetician', 'Skin Care Specialist', 'Manicurist', 'Barber'
        ];

        public $writing = [
            'Journalist', 'Copy Editor', 'Editor/Proofreader', 'Content Creator', 'Speechwriter', 'Communications Director', 'Screenwriter', 'Technical Writer', 'Columnist', 'Public Relations Specialist', 'Proposal Writer', 'Content Strategist', 'Grant Writer', 'Video Game Writer', 'Translator', 'Film Critic', 'Copywriter', 'Travel Writer', 'Social Media Specialist', 'Ghostwriter'
        ];

        public $labor = [
            'Warehouse Worker', 'Painter', 'Truck Driver', 'Heavy Equipment Operator', 'Welding', 'Physical Therapy Assistant', 'Housekeeper', 'Landscaping Worker', 'Landscaping Assistant', 'Mover'
        ];

        public $animals = [
            'Animal Breeder', 'Veterinary Assistant', 'Farm Worker', 'Animal Shelter Worker', 'Dog Walker / Pet Sitter', 'Zoologist', 'Animal Trainer', 'Service Dog Trainer', 'Animal Shelter Manager', 'Animal Control Officer'
        ];

        public $driving = [
            'Delivery Driver', 'School Bus Driver', 'Truck Driver', 'Tow Truck Operator', 'UPS Driver', 'Mail Carrier', 'Recyclables Collector', 'Courier', 'Bus Driver', 'Cab Driver'
        ];
        
        public $volunteer = [
            'Animal Shelter Board Member', 'Office Volunteer', 'Animal Shelter Volunteer', 'Hospital Volunteer', 'Youth Volunteer', 'Food Kitchen Worker', 'Homeless Shelter Worker', 'Conservation Volunteer', 'Meals on Wheels Driver', 'Habitat for Humanity Builder', 'Emergency Relief Worker', 'Red Cross Volunteer', 'Community Food Project Worker', 'Women’s Shelter Jobs', 'Suicide Hotline Volunteer', 'School Volunteer', 'Community Volunteer Jobs', 'Sports Volunteer', 'Church Volunteer'
        ];

        public $otherJob = [
            'Archivist', 'Actuary', 'Architect', 'Personal Assistant', 'Entrepreneur', 'Security Guard', 'Mechanic', 'Recruiter', 'Mathematician', 'Locksmith', 'Management Consultant', 'Shelf Stocker', 'Caretaker or House Sitter', 'Library Assistant', 'Translator', 'HVAC Technician', 'Attorney', 'Paralegal', 'Executive Assistant', 'Personal Assistant', 'Bank Teller', 'Parking Attendant', 'Machinery Operator', 'Manufacturing Assembler', 'Funeral Attendant', 'Assistant Golf Professional', 'Yoga Instructor'
        ];

        public $db = [
            'Business Data Analyst', 'Data Engineer', 'Data Governance Manager', 'Data Manager', 'Data Warehouse Architect', 'Database Administrator', 'Database Administrator DBA', 'Database Developer', 'Database Engineer', 'DBA', 'DBA Oracle', 'Developer SQL', 'Head Of Data', 'Junior DBA', 'Oracle Consultant', 'Oracle Database Administrator', 'Oracle DBA', 'SQL Data Analyst', 'SQL Database Administrator', 'Sql Dba', 'SQL Developer', 'SQL Server DBA', 'SQL Server Developer', 'Database Administrator SQL Server', 'Database Analyst', 'Database Manager', 'Datacentre Manager', 'Db2 Dba', 'Excel Data Analyst', 'Head Of Data Management', 'Junior SQL DBA', 'MySQL DBA', 'Data Scientist', 'Data Architect', 'Cloud Architect', 'Data Scientist'
        ];
        
    }    
?> 