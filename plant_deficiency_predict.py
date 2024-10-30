import sys
from PIL import Image
import numpy as np
import tensorflow as tf

# Define your labels based on the classes you used in training
labels = ['nitrogen-N', 'phosphorus-P', 'potassium-K']  # Update with actual class labels

def preprocess_image(image_path):
    """
    Preprocess the image for prediction.
    Resize and normalize the image to fit the model input.
    """
    image = Image.open(image_path)
    image = image.resize((224, 224))  # Resize to match model input
    image_array = np.array(image) / 255.0  # Normalize pixel values
    image_array = np.expand_dims(image_array, axis=0)  # Add batch dimension
    return image_array

def main(image_path):
    # Load the trained model
    model = tf.keras.models.load_model('my_model.h5')
    
    # Preprocess the image
    image_array = preprocess_image(image_path)
    
    # Make a prediction
    predictions = model.predict(image_array)
    
    # Get the class with the highest prediction probability
    predicted_class = np.argmax(predictions, axis=1)
    
    # Get the predicted label based on the class index
    predicted_label = labels[predicted_class[0]]
    
    # Output the predicted label
    print(predicted_label)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Error: No image file path provided.")
        sys.exit(1)
    
    image_path = sys.argv[1]
    main(image_path)
