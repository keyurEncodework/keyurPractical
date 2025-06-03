# User Seeder & API Testing Guide

## Overview
The seeder creates **75+ users** with diverse data specifically designed to test all search and filtering functionality of your User API.

## Setup Instructions

### 1. Run the Seeder
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Or just run the seeder
php artisan db:seed --class=UserSeeder
```

### 2. Verify Data Creation
```bash
# Check total users created
php artisan tinker
>>> App\Models\User::count()
>>> App\Models\Address::count()
```

## Test Data Structure

### 📊 **Data Distribution**
- **75+ total users** with realistic Indian data
- **150+ addresses** (1-4 addresses per user)
- **Age ranges**: 18-65 years (distributed across different age groups)
- **Cities**: 20+ Indian cities including Mumbai, Delhi, Bangalore, etc.
- **Name diversity**: Common Indian names + specific test names

### 🎯 **Specific Test Data Created**

#### **1. Search Test Users (10 users)**
Users with specific names for testing search functionality:
- John Doe, Jane Smith, Michael Johnson
- Sarah Williams, David Brown, Jennifer Davis
- Robert Miller, Lisa Wilson, James Moore, Mary Taylor

#### **2. Age Range Distribution**
- **18-25 years**: 5 users (Young adults)
- **26-35 years**: 8 users (Adults)  
- **36-50 years**: 7 users (Middle-aged)
- **51-65 years**: 5 users (Seniors)

#### **3. City-Specific Users (30 users)**
- **Mumbai**: 8 users
- **Delhi**: 6 users
- **Bangalore**: 5 users
- **Ahmedabad**: 4 users
- **Chennai**: 3 users
- **Pune**: 4 users

## 🧪 **API Testing Scenarios**

### **1. Search Query Testing**

**Test Name Search:**
```bash
GET /api/users?searchQuery=john
# Should return: John Doe and any other Johns

GET /api/users?searchQuery=smith  
# Should return: Jane Smith and other Smiths
```

**Test Email Search:**
```bash
GET /api/users?searchQuery=test.com
# Should return users with @test.com emails

GET /api/users?searchQuery=gmail
# Should return users with Gmail addresses
```

**Test Partial Search:**
```bash
GET /api/users?searchQuery=da
# Should return: David Brown, any Davies, etc.
```

### **2. Age Range Testing**

**Young Adults:**
```bash
GET /api/users?minAge=18&maxAge=25
# Should return ~5 users aged 18-25
```

**Working Professionals:**
```bash
GET /api/users?minAge=26&maxAge=35
# Should return ~8 users aged 26-35
```

**Middle-aged:**
```bash
GET /api/users?minAge=36&maxAge=50
# Should return ~7 users aged 36-50
```

**Seniors:**
```bash
GET /api/users?minAge=51
# Should return ~5 users aged 51+
```

**Specific Age Range:**
```bash
GET /api/users?minAge=30&maxAge=40
# Should return users between 30-40 years
```

### **3. City Filtering Testing**

**Major Cities:**
```bash
GET /api/users?city=Mumbai
# Should return ~8 users from Mumbai

GET /api/users?city=Delhi
# Should return ~6 users from Delhi

GET /api/users?city=Bangalore
# Should return ~5 users from Bangalore
```

**Partial City Search:**
```bash
GET /api/users?city=Ahm
# Should return users from Ahmedabad
```

### **4. Combined Search Testing**

**Name + Age:**
```bash
GET /api/users?searchQuery=john&minAge=25&maxAge=45
# Should return Johns between 25-45 years
```

**City + Age:**
```bash
GET /api/users?city=Mumbai&minAge=30&maxAge=40
# Should return Mumbai users aged 30-40
```

**All Filters Combined:**
```bash
GET /api/users?searchQuery=smith&minAge=25&maxAge=50&city=Delhi
# Should return Smiths from Delhi aged 25-50
```

### **5. Pagination Testing**

**Page Navigation:**
```bash
GET /api/users?page=1
GET /api/users?page=2
GET /api/users?page=3
# Test pagination with 15 users per page
```

**Search with Pagination:**
```bash
GET /api/users?city=Mumbai&page=1
# Test pagination with filtered results
```

## 🔍 **Expected Results Guide**

### **Search Functionality**
- ✅ **Name search**: Should find partial matches in first_name and last_name
- ✅ **Email search**: Should find partial matches in email addresses
- ✅ **Case-insensitive**: Should work regardless of case

### **Age Filtering**
- ✅ **minAge only**: Returns users >= specified age
- ✅ **maxAge only**: Returns users <= specified age  
- ✅ **Both**: Returns users within the age range
- ✅ **Edge cases**: Should handle exact age matches

### **City Filtering**
- ✅ **Exact match**: Should find users with exact city names
- ✅ **Partial match**: Should find cities containing the search term
- ✅ **Multiple addresses**: Should find users who have ANY address in the specified city

## 🎲 **Random Testing Data**

In addition to structured test data, the seeder creates **40+ random users** with:
- Random Indian names using Faker
- Diverse age distribution (18-60 years)
- Random cities from 20+ Indian cities
- Multiple addresses per user (1-4 addresses)
- Mix of Home and Office address types

## 📈 **Performance Testing**

With 75+ users and 150+ addresses, you can test:
- **Query performance** with joins and filtering
- **Pagination efficiency** with large datasets
- **Search speed** across multiple fields
- **Memory usage** with eager loading

## 🚀 **Quick Test Commands**

```bash
# Test basic functionality
curl "http://localhost:8000/api/users"

# Test search
curl "http://localhost:8000/api/users?searchQuery=john"

# Test age filtering  
curl "http://localhost:8000/api/users?minAge=25&maxAge=35"

# Test city filtering
curl "http://localhost:8000/api/users?city=Mumbai"

# Test combined filters
curl "http://localhost:8000/api/users?searchQuery=smith&minAge=30&city=Delhi"
```

## 💡 **Additional Testing Tips**

1. **Use Postman/Insomnia** for easier API testing with collections
2. **Check response times** - should be under 200ms for most queries
3. **Verify pagination** - ensure proper page links and counts
4. **Test edge cases** - empty results, invalid parameters
5. **Check data consistency** - ensure all users have at least one address

The seeder provides comprehensive test data covering all API functionality with realistic Indian user data!