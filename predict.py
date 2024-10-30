import joblib
import numpy as np
import sys

# Update the paths to the models
model_N_path = 'C://xampp//htdocs//project_sem4//model_N.pkl'
model_P_path = 'C://xampp//htdocs//project_sem4//model_P.pkl'
model_K_path = 'C://xampp//htdocs//project_sem4//model_K.pkl'
model_pH_path = 'C://xampp//htdocs//project_sem4//model_pH.pkl'

# Load models
model_N = joblib.load(model_N_path)
model_P = joblib.load(model_P_path)
model_K = joblib.load(model_K_path)
model_pH = joblib.load(model_pH_path)

# Check for correct number of arguments
if len(sys.argv) != 6:
    print("Usage: python predict.py <crop_type> <nitrogen> <potassium> <phosphorus> <ph>")
    print(f"Arguments received: {sys.argv}")
    sys.exit(1)

# Retrieve arguments
try:
    crop_type = sys.argv[1]
    nitrogen = float(sys.argv[2])
    potassium = float(sys.argv[3])
    phosphorus = float(sys.argv[4])
    ph = float(sys.argv[5])
    
    # Map crop_type to its encoded label
    crop_labels = {'banana': 0, 'rice': 1, 'coconut': 2, 'coffee': 3}
    if crop_type not in crop_labels:
        print("Error: Invalid crop type.")
        sys.exit(1)
    
    label_encoded = crop_labels[crop_type]

except ValueError:
    print("Error: Invalid input. Ensure all inputs are numeric.")
    sys.exit(1)

# Prepare input for each model
X_N = np.array([[nitrogen, label_encoded]])
X_P = np.array([[potassium, label_encoded]])
X_K = np.array([[phosphorus, label_encoded]])
X_pH = np.array([[ph, label_encoded]])

# Predict
pred_N = model_N.predict(X_N)[0]
pred_P = model_P.predict(X_P)[0]
pred_K = model_K.predict(X_K)[0]
pred_pH = model_pH.predict(X_pH)[0]

# Print results
print(f"{pred_N},{pred_P},{pred_K},{pred_pH}")
