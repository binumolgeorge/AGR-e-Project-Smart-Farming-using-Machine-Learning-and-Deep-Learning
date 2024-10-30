import pandas as pd
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error
import numpy as np
import joblib

# Load the dataset
df = pd.read_csv("Dataset/Soil_testing.csv", encoding='latin-1')
print(df.columns)

# Apply Label Encoding to the 'label' column
label_encoder = LabelEncoder()
df['label_encoded'] = label_encoder.fit_transform(df['label'])

# Define features and labels
X = df[['N', 'P', 'K', 'ph', 'label_encoded']]
y_N = df['N_deficiency']
y_P = df['P_deficiency']
y_K = df['K_deficiency']
y_pH = df['pH_deficiency']

# Split data into training and test sets
X_train, X_test, y_N_train, y_N_test = train_test_split(X, y_N, test_size=0.2, random_state=42)
X_train, X_test, y_P_train, y_P_test = train_test_split(X, y_P, test_size=0.2, random_state=42)
X_train, X_test, y_K_train, y_K_test = train_test_split(X, y_K, test_size=0.2, random_state=42)
X_train, X_test, y_pH_train, y_pH_test = train_test_split(X, y_pH, test_size=0.2, random_state=42)

# Train models
model_N = LinearRegression()
model_N.fit(X_train[['N', 'label_encoded']], y_N_train)

model_P = LinearRegression()
model_P.fit(X_train[['P', 'label_encoded']], y_P_train)

model_K = LinearRegression()
model_K.fit(X_train[['K', 'label_encoded']], y_K_train)

model_pH = LinearRegression()
model_pH.fit(X_train[['ph', 'label_encoded']], y_pH_train)

# Evaluate models
pred_N = model_N.predict(X_test[['N', 'label_encoded']])
pred_P = model_P.predict(X_test[['P', 'label_encoded']])
pred_K = model_K.predict(X_test[['K', 'label_encoded']])
pred_pH = model_pH.predict(X_test[['ph', 'label_encoded']])

rmse_N = np.sqrt(mean_squared_error(y_N_test, pred_N))
rmse_P = np.sqrt(mean_squared_error(y_P_test, pred_P))
rmse_K = np.sqrt(mean_squared_error(y_K_test, pred_K))
rmse_pH = np.sqrt(mean_squared_error(y_pH_test, pred_pH))

print(f"RMSE for Nitrogen: {rmse_N}")
print(f"RMSE for Phosphorus: {rmse_P}")
print(f"RMSE for Potassium: {rmse_K}")
print(f"RMSE for pH: {rmse_pH}")

# Save models
joblib.dump(model_N, 'model_N.pkl')
joblib.dump(model_P, 'model_P.pkl')
joblib.dump(model_K, 'model_K.pkl')
joblib.dump(model_pH, 'model_pH.pkl')

print("Models saved successfully.")
