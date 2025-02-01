import pandas as pd
import re


def clean_merged_cells(input_path, output_path):
    # Load CSV with proper encoding
    df = pd.read_csv(input_path)
    
    # Forward-fill merged cells for key columns
    columns_to_fill = [
        'university_name',
        'Program Name (Award)', 
        'Admission Requirements',
        'Program Duration (Yrs)'
    ]
    
    # Fill NaN values with previous valid values
    for col in columns_to_fill:
        df[col] = df[col].ffill()
    
    # Clean tuition fees column
    def clean_fees(fee):
        if pd.isna(fee) or fee == "":
            return None, None
        # Extract currency and amount using regex
        currency_match = re.search(r'(TSH|USD)', str(fee))
        amount_match = re.search(r'[\d,]+', str(fee))
        
        currency = currency_match.group(0) if currency_match else 'TSH'
        amount = amount_match.group(0).replace(',', '') if amount_match else None
        
        return f"{currency} {amount}" if amount else None

    df['Tuition Fees'] = df['Tuition Fees'].apply(clean_fees)
    
    # Split fee into separate currency and amount columns
    df[['fee_currency', 'fee_amount']] = df['Tuition Fees'].str.split(' ', expand=True)
    df.drop('Tuition Fees', axis=1, inplace=True)
    
    # Save cleaned data
    df.to_csv(output_path, index=False)
    return df

# Usage
input_csv = "courses.csv"
output_csv = "courses_cleaned.csv"
clean_merged_cells(input_csv, output_csv)