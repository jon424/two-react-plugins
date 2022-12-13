import "./frontend.scss";
import React, { useState } from "react";
import ReactDOM from "react-dom";

const divsToUpdate = document.querySelectorAll(".boilerplate-update-me-too");

divsToUpdate.forEach((div) => {
  const data = JSON.parse(div.querySelector("pre").innerText);
  ReactDOM.render(<CountComponent {...data} />, div);
  div.classList.remove("boilerplate-update-me-too");
});

function CountComponent(props) {
  // const [showSkyColor, setShowSkyColor] = useState(false);
  // const [showGrassColor, setShowGrassColor] = useState(false);

  // return (
  //   <div className="boilerplate-frontend">
  //     <p>
  //       <button onClick={() => setShowSkyColor((prev) => !prev)}>
  //         Toggle view sky color!!!!!
  //       </button>
  //       {showSkyColor && <span>{props.skyColor}</span>}
  //     </p>
  //     <p>
  //       <button onClick={() => setShowGrassColor((prev) => !prev)}>
  //         Toggle view grass color...
  //       </button>
  //       {showGrassColor && <span>{props.grassColor}</span>}
  //     </p>
  //   </div>
  // );
  const [count, setCount] = useState(0);

  return (
    <div className="boilerplate-frontend-2">
      <p>You clicked {count} times</p>
      <button onClick={() => setCount(count + 1)}>Click me</button>
    </div>
  );
}
