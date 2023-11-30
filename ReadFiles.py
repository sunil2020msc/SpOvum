import os
import datetime
import pandas as pd
import mysql.connector

def extract_data(file_path):
    try:
        # Read data from DM_values.txt into a DataFrame
        df = pd.read_csv(file_path, delim_whitespace=True, skip_blank_lines=True, header=None, names=['colA', 'colB'])
        
        # Drop rows with any NaN values
        df = df.dropna()

        return df

    except pd.errors.EmptyDataError:
        return pd.DataFrame(columns=['colA', 'colB'])


def insert_into_database(data, folder_name, date, cursor):
    # Insert data into the MySQL database
    query = "INSERT INTO files (folderName, date, fileName, colA, colB) VALUES (%s, %s, %s, %s, %s)"
    for i, row in enumerate(data.itertuples(), start=1):
        values = (folder_name, date, f"{folder_name}_{i}", row.colA, row.colB)
        try:
            cursor.execute(query, values)
        except Exception as e:
            print(f"Error inserting data into the database: {e}")

if __name__ == "__main__":
    # Change these to your MySQL database details
    db_config = {
        "host": "localhost",
        "user": "root",
        "password": "",
        "database": "interview"
    }

    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()

    # Change this to the parent directory containing the three folders
    parent_directory = "./"

    try:
        for folder_name in os.listdir(parent_directory):
            folder_path = os.path.join(parent_directory, folder_name)

            if os.path.isdir(folder_path):
                # Extract date from folder name
                date = datetime.datetime.strptime(folder_name[:8], "%Y%m%d").date()

                # Form the complete file path
                file_path = os.path.join(folder_path, "DM_values.txt")

                # Extract data from the file
                data = extract_data(file_path)

                # Insert data into the database
                insert_into_database(data, folder_name, date, cursor)

        connection.commit()
        print("Data inserted into the database successfully.")
    finally:
        connection.close()
