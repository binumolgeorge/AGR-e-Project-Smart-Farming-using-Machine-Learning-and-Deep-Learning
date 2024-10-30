from flask import Flask, request, jsonify
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import numpy as np
import io
from PIL import Image

app = Flask(__name__)

# Load the trained model
model = load_model(r"C:/Users/HP/flask_api/model.h5")

# Define class labels
class_labels = ['Nitrogen Deficiency', 'Phosphorus Deficiency', 'Potassium Deficiency', 'Healthy']

@app.route('/predict', methods=['POST'])
def predict():
    if 'file' not in request.files:
        return jsonify({'error': 'No file provided'}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    try:
        # Read and preprocess the image
        img = Image.open(io.BytesIO(file.read()))
        img = img.resize((224, 224))
        img_array = np.array(img)

        # Convert image array to float32 before normalizing
        img_array = img_array.astype('float32')
        img_array = np.expand_dims(img_array, axis=0)
        img_array /= 255.0  # Normalize to [0, 1]

        # Make a prediction
        predictions = model.predict(img_array)
        predicted_class = np.argmax(predictions[0])
        predicted_label = class_labels[predicted_class]

        return jsonify({'prediction': predicted_label})
    except Exception as e:
        print(f"Error processing the image: {str(e)}")
        return jsonify({'error': 'Error processing the image'}), 500

if __name__ == '__main__':
    app.run(debug=True, port=5000)  # Ensure Flask runs on port 5000
