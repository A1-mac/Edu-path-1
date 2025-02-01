import requests
from bs4 import BeautifulSoup
import csv

# Base URL for the TCU page
base_url = "https://www.tcu.go.tz/services/accreditation/universities-registered-tanzania"

# CSV file to save the data
csv_file = 'universities.csv'

# Open CSV for writing
with open(csv_file, 'w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(['S/N', 'Name', 'Head Office', 'Type', 'Status', 'Action'])  # Header row

    page_url = base_url  # Start with the base URL
    while True:
        print(f"Scraping: {page_url}")
        
        # Fetch the page
        response = requests.get(page_url)
        response.raise_for_status()  # Raise an error if the request fails

        soup = BeautifulSoup(response.text, 'html.parser')
        table = soup.find('table', class_="cols-6 data-grid")

        if not table:
            print("No table found on this page. Stopping.")
            break

        rows = table.find('tbody').find_all('tr')
        if not rows:
            print("No rows found on this page. Stopping.")
            break

        # Loop through rows and extract data
        for row in rows:
            cells = row.find_all('td')
            sn = cells[0].text.strip()
            name = cells[1].text.strip()
            head_office = cells[2].text.strip()
            uni_type = cells[3].text.strip()
            status = cells[4].text.strip()
            action_link = cells[5].find('a')['href'] if cells[5].find('a') else None

            # Write to CSV
            writer.writerow([sn, name, head_office, uni_type, status, action_link])

        # Look for the "Go to next page" link
        next_page_link = soup.find('a', title="Go to next page")
        if not next_page_link:
            print("No 'Go to next page' link found. Scraping complete.")
            break

        # Update the URL for the next page
        page_url = next_page_link['href']
        if not page_url.startswith("http"):
            page_url = requests.compat.urljoin(base_url, page_url)

print("Data scraping complete. Saved to universities.csv.")
