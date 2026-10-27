-- Expand attorneys table with detailed profile fields
ALTER TABLE attorneys
ADD COLUMN email VARCHAR(100),
ADD COLUMN phone VARCHAR(20),
ADD COLUMN specialty TEXT,
ADD COLUMN years_experience INT,
ADD COLUMN education TEXT,
ADD COLUMN bar_admissions TEXT,
ADD COLUMN achievements TEXT,
ADD COLUMN notable_cases TEXT,
ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active',
ADD COLUMN display_order INT DEFAULT 0;

-- Update existing attorneys with sample detailed data
UPDATE attorneys SET
    email = 'jdoe@lawyerson.com',
    phone = '(555) 123-4501',
    specialty = 'Corporate Law, Mergers & Acquisitions, Securities Law',
    years_experience = 25,
    education = 'J.D., Harvard Law School, 1998; B.A. Economics, Yale University, 1995',
    bar_admissions = 'New York State Bar, California State Bar, U.S. Supreme Court',
    achievements = 'Named "Best Corporate Attorney" by Legal Excellence Magazine (2020-2023); Successfully led 150+ corporate mergers totaling $50B+ in transaction value; Recognized as Top 100 Lawyers in America by National Law Journal',
    notable_cases = 'Led legal team for TechCorp merger with GlobalSoft ($8.5B deal); Represented Fortune 500 company in landmark securities litigation; Advised multiple startups through successful IPO processes',
    status = 'active',
    display_order = 1
WHERE name = 'Jonathan Doe';

UPDATE attorneys SET
    email = 'sjenkins@lawyerson.com',
    phone = '(555) 123-4502',
    specialty = 'Family Law, Divorce & Custody, Prenuptial Agreements',
    years_experience = 18,
    education = 'J.D., Stanford Law School, 2005; M.A. Psychology, UCLA, 2002; B.A. Sociology, UC Berkeley, 1999',
    bar_admissions = 'California State Bar, Nevada State Bar',
    achievements = 'Top Family Law Attorney Award (State Bar Association, 2019-2023); Successfully resolved 500+ family law cases; Certified Family Law Specialist; Published author on child custody best practices',
    notable_cases = 'Landmark custody case establishing new precedent for shared parenting; High-profile divorce settlement for celebrity client; Complex international custody dispute resolution',
    status = 'active',
    display_order = 2
WHERE name = 'Sarah Jenkins';

UPDATE attorneys SET
    email = 'mchang@lawyerson.com',
    phone = '(555) 123-4503',
    specialty = 'Real Estate Law, Commercial Property, Zoning & Land Use',
    years_experience = 12,
    education = 'J.D., Columbia Law School, 2011; B.S. Real Estate Development, University of Pennsylvania, 2008',
    bar_admissions = 'New York State Bar, New Jersey State Bar',
    achievements = 'Rising Star in Real Estate Law (Super Lawyers, 2021-2023); Negotiated 200+ commercial real estate transactions; Expert in complex zoning and land use regulations',
    notable_cases = 'Multi-billion dollar commercial development project in Manhattan; Resolved major zoning dispute for mixed-use development; Represented major REIT in portfolio acquisition',
    status = 'active',
    display_order = 3
WHERE name = 'Michael Chang';

UPDATE attorneys SET
    email = 'erostova@lawyerson.com',
    phone = '(555) 123-4504',
    specialty = 'Criminal Defense, White Collar Crime, Trial Litigation',
    years_experience = 15,
    education = 'J.D., University of Chicago Law School, 2008; B.A. Criminal Justice, Georgetown University, 2005',
    bar_admissions = 'Illinois State Bar, Federal Courts (Northern District), U.S. Court of Appeals',
    achievements = '95% success rate in criminal defense cases; Named "Defense Attorney of the Year" (2022); Argued before State Supreme Court; Former Assistant District Attorney',
    notable_cases = 'Complete exoneration in high-profile white collar fraud case; Successfully defended multiple federal criminal cases; Landmark appeal victory establishing new precedent',
    status = 'active',
    display_order = 4
WHERE name = 'Elena Rostova';

-- Verify updates
SELECT name, email, phone, specialty, years_experience FROM attorneys ORDER BY display_order;
