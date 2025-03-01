#!/usr/bin/env python3
import sys
import json
import warnings
import requests # type: ignore
from bs4 import BeautifulSoup# type: ignore
import urllib3 # type: ignore

# Ignore urllib3 SSL warnings
warnings.filterwarnings("ignore", category=UserWarning, module="urllib3")

# Disable SSL warnings
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def fetch_student_results(year, exam_type, school_number, student_number):
    """
    Fetch and parse NECTA results for a single student using the provided logic
    for ACSEE and CSEE.
    """
    exam_type = exam_type.lower()
    year = int(year)
    school_number = school_number.lower()
    examination_number = f"{school_number.upper()}/{student_number}"

    # Build URL and determine table index based on exam type and year
    if exam_type == "acsee":
        if year == 2023:
            url = f"https://onlinesys.necta.go.tz/results/2023/acsee/results/{school_number}.htm"
        else:
            url = f"https://onlinesys.necta.go.tz/results/{year}/acsee/results/{school_number}.htm"
        
        if school_number.startswith("p"):
            index = 2 if year > 2019 else 0
        else:
            index = 2 if year >= 2019 else 0

    elif exam_type == "csee":
        if year == 2023:
            url = f"https://onlinesys.necta.go.tz/results/2023/csee/results/{school_number}.htm"
        elif year == 2021:
            url = f"https://onlinesys.necta.go.tz/results/2021/csee/results/{school_number}.htm"
        elif year == 2024:
            url = f"https://matokeo.necta.go.tz/results/2024/acsee/results/{school_number}.htm"
        elif year > 2014:
            url = f"https://onlinesys.necta.go.tz/results/{year}/csee/results/{school_number}.htm"
        else:
            url = f"https://onlinesys.necta.go.tz/results/{year}/csee/{school_number}.htm"
        
        if school_number.startswith("p"):
            index = 2 if year > 2018 else 0
        else:
            index = 2 if year > 2018 else 0
    else:
        return {"error": "Unsupported exam type. Use CSEE or ACSEE."}

    # Fetch the results page
    try:
        data = requests.get(url, verify=False)
        data.raise_for_status()
    except Exception as e:
        return {"error": f"Failed to retrieve data from NECTA: {e}"}

    # Parse HTML with BeautifulSoup
    soup = BeautifulSoup(data.text, 'html.parser')

    # Search for the student's row in the results table (assuming the results are in the table at the computed index)
    try:
        tables = soup.find_all("table")
        results_table = tables[index]
    except Exception:
        return {"error": "Results table not found in the page."}

    student_data = None
    for tr in results_table.find_all("tr"):
        cells = [td.get_text(strip=True) for td in tr.find_all("td")]
        if cells and cells[0] == examination_number:
            student_data = {
                "examination_number": examination_number,
                "year_of_exam": year,
                "exam_type": exam_type.upper(),
                "school_name": "Placeholder School",  # You can enhance this to extract actual school name if available
                "gender": cells[1] if len(cells) > 1 else "*",
                "points": cells[2] if len(cells) > 2 else "*",
                "division": cells[3] if len(cells) > 3 else "*",
                "subjects": {}
            }
            if len(cells) > 4:
                # Example extraction: adjust the regex or parsing as needed for subjects and grades
                import re
                subjects = re.findall(r'([A-Z\s\/]+)-\s*\'([A-F])\'', cells[4])
                if subjects:
                    student_data["subjects"] = {subj.strip(): grade for subj, grade in subjects}
            break

    if not student_data:
        return {"error": f"Student {examination_number} not found."}

    return student_data

def main():
    # Expecting arguments: year, exam_type, school_number, student_number
    if len(sys.argv) < 5:
        print(json.dumps({"error": "Insufficient arguments. Usage: python3 get_results.py <year> <exam_type> <school_number> <student_number>"}))
        sys.exit(1)

    year = sys.argv[1]
    exam_type = sys.argv[2]
    school_number = sys.argv[3]
    student_number = sys.argv[4]

    try:
        result = fetch_student_results(year, exam_type, school_number, student_number)
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({"error": f"Unhandled exception: {str(e)}"}))

if __name__ == "__main__":
    main()
